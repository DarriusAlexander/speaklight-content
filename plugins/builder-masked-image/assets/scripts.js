(function ($) {
"use strict";
	/*load_image*/
	!function(e,n){"use strict";e.fn.loadImage=function(n){var r=this,t=e.Deferred(),d=function(){r.unbind("load",d),r.unbind("error",i),t.resolve(r)},i=function(){r.unbind("load",d),r.unbind("error",i),t.reject(r)};return r.bind("error",i),r.bind("load",d),r.attr("src",n),(r[0].complete||r[0].readyState)&&d(),t},e.loadImage=function(n,r){if("undefined"==e.type(r))r=n,n=e("<img />");else n=e(n);return n.loadImage(r)}}(jQuery);
	/*github.com/nodeca/glur*/
	var a0,a1,a2,a3,b1,b2,left_corner,right_corner;function gaussCoef(a){a<.5&&(a=.5);var r=Math.exp(.527076)/a,e=Math.exp(-r),n=Math.exp(-2*r),o=(1-e)*(1-e)/(1+2*r*e-n);return a0=o,a1=o*(r-1)*e,a2=o*(r+1)*e,a3=-o*n,b1=2*e,b2=-n,new Float32Array([a0,a1,a2,a3,b1,b2,left_corner=(a0+a1)/(1-b1-b2),right_corner=(a2+a3)/(1-b1-b2)])}function convolveRGBA(a,r,e,n,o,t){var f,b,c,v,l,i,u,A,h,g,s,w,x,y,B,G,M,R,_,p,C,F,U,m,d,j,k,q,z,D;for(d=0;d<t;d++){for(U=d,m=0,c=(f=a[F=d*o])>>8&255,v=f>>16&255,l=f>>24&255,y=R=(b=255&f)*n[6],B=_=c*n[6],G=p=v*n[6],M=C=l*n[6],k=n[0],q=n[1],z=n[4],D=n[5],j=0;j<o;j++)g=(i=255&(f=a[F]))*k+b*q+y*z+R*D,s=(u=f>>8&255)*k+c*q+B*z+_*D,w=(A=f>>16&255)*k+v*q+G*z+p*D,x=(h=f>>24&255)*k+l*q+M*z+C*D,R=y,_=B,p=G,C=M,y=g,B=s,G=w,M=x,b=i,c=u,v=A,l=h,e[m]=y,e[m+1]=B,e[m+2]=G,e[m+3]=M,m+=4,F++;for(m-=4,U+=t*(o-1),c=(f=a[--F])>>8&255,v=f>>16&255,l=f>>24&255,y=R=(b=255&f)*n[7],B=_=c*n[7],G=p=v*n[7],M=C=l*n[7],i=b,u=c,A=v,h=l,k=n[2],q=n[3],j=o-1;j>=0;j--)g=i*k+b*q+y*z+R*D,s=u*k+c*q+B*z+_*D,w=A*k+v*q+G*z+p*D,x=h*k+l*q+M*z+C*D,R=y,_=B,p=G,C=M,y=g,B=s,G=w,M=x,b=i,c=u,v=A,l=h,i=255&(f=a[F]),u=f>>8&255,A=f>>16&255,h=f>>24&255,f=(e[m]+y<<0)+(e[m+1]+B<<8)+(e[m+2]+G<<16)+(e[m+3]+M<<24),r[U]=f,F--,m-=4,U-=t}}function blurRGBA(a,r,e,n){if(n){var o=new Uint32Array(a.buffer),t=new Uint32Array(o.length),f=new Float32Array(4*Math.max(r,e)),b=gaussCoef(n);convolveRGBA(o,t,f,b,r,e,n),convolveRGBA(t,o,f,b,e,r,n)}}

	var isWorking = true,
            do_masked_image = function (el) {
		var items =  $('.module-masked-image',el);
		if ( el && el[0].classList.contains('module-masked-image') ) {
			items = items.add(el);
		}
		items.each( function(){
			var module = $( this ),
				canvas = $( 'canvas', module );
			if ( canvas.length < 1 ) {
				return;
			}
			var ctx = canvas[0].getContext('2d'),
				link = $( '.bmi-image-wrap a', module ).clone().empty(),
				leftAlign = module.hasClass( 'image-left' ),
				tempImg = document.createElement( 'img' ),
				tempMask = document.createElement( 'img' );

			$.loadImage( tempImg, $( '.bmi-image-wrap img', module ).attr( 'src' ) ).done( function(){
				$.loadImage( tempMask, canvas.data( 'mask' ) ).done( function(){

					module.prev( '.tb_slider_loader' ).remove();

					var textEl = $( '.bmi-text-wrap', module ),
						width = canvas.width(),
						height = canvas.height();

					canvas[0].width = width;
					canvas[0].height = height;

					var mask_flip = canvas.data( 'mask-flip' ),
						flip_data = {
							none : [ 1, 1, 0, 0 ],
							horizontal : [ -1, 1, width * -1, 0 ],
							vertical : [ 1, -1, 0, height * -1 ],
							both : [ -1, -1, width * -1, height * -1 ]
						};

					ctx.save();
					ctx.scale( flip_data[ mask_flip ][0], flip_data[ mask_flip ][1] );
					ctx.drawImage( tempMask, flip_data[ mask_flip ][2], flip_data[ mask_flip ][3], width, height );

					if ( parseInt( canvas.data( 'feather' ) ) > 0 ) {
						var imageData = ctx.getImageData( 0, 0, width, height );
						blurRGBA( imageData.data, width, height, parseInt( canvas.data( 'feather' ) ) );
						ctx.putImageData( imageData, 0, 0 );
					}

					ctx.restore();
					ctx.globalCompositeOperation = 'source-atop';
					ctx.drawImage( tempImg, 0, 0, width, height );

					if ( module.find( '.bmi-text-wrap' ).length>0 && ( module.hasClass( 'image-left' ) || module.hasClass( 'image-right' ) ) ) {
						textEl.find( '> a' ).remove();
						module.removeClass( 'stack' );
						// if there's not enough room for the caption gutter, do not add the gutter
						if ( module.width() <= canvas.width() ) {
							module.addClass( 'stack' ).css( { visibility : 'visible' } );
							return;
						}

						/* make a clone of canvas and calculate empty pixels */
						var canvasc = canvas.clone(),
							ctxc = canvasc[0].getContext( '2d' ),
							gutter = canvas.data( 'gutter' ) ? parseInt( canvas.data( 'gutter' ) ) : 0,
							gutter_h = canvas.data( 'gutter-h' ) ? parseInt( canvas.data( 'gutter-h' ) ) : 0,
							width = canvas.width() + gutter,
							height = canvas.height() + gutter_h,
							_r = [];
						canvasc[0].width = width;
						canvasc[0].height = height;
						ctxc.save();
						ctxc.scale( flip_data[ mask_flip ][0], flip_data[ mask_flip ][1] );
						ctxc.drawImage( tempMask, flip_data[ mask_flip ][2], flip_data[ mask_flip ][3], width, height );
						ctxc.restore();
						ctxc.globalCompositeOperation = 'source-atop';
						ctxc.drawImage( tempImg, 0, 0, width, height );

						rowLoop : for ( var i = 0,h=height / 10; i <= h; ++i ) {
							_r[ i ] = 0;
								columnLoop : for ( var j = 1,w=width / 10; j <= w; ++j ) {
								var imgd = ctxc.getImageData( leftAlign ? width - ( j * 10 ) : j * 10, i * 10, 10, 10 ),
									empty = true;
								// pixels
								for ( var k = 0,l=imgd.data.length; k <l ; ++k ) {
									if ( imgd.data[k] !== 0 ) {
										empty = false;
										break columnLoop;
									}
								}
								if ( empty ) {
									++_r[ i ];
								}
							}
						}
						_r = _r.reverse();
						for(var i = 0,l=_r.length;i<l;++i){
							var w = width - ( _r[i] * 10 );
							textEl.prepend( link.clone().css( { width: w, height: 10, visibility: 'visible' } ).addClass( 'bmi-spacer' ) );
						}
					}

					module.css( 'visibility' , 'visible'  );
				} );
			} );
		} );
                isWorking=null;
	};

	if ( Themify.is_builder_active ) {
            if( Themify.is_builder_loaded ) {
                    do_masked_image();
            }
            else{
                isWorking=null;
            }
	} else {
            var callback = function(){
                $( window ).on( 'tfsmartresize',function(){
                    do_masked_image();
                });
            };
            if (window.loaded) {
                    callback();
            }
            else{
                window.addEventListener( 'load', callback,{once:true,passive:true});
            }
	}
	Themify.body.on( 'builder_load_module_partial', function(e,el,type){
		if(isWorking===null){
			do_masked_image(el);
		}
	});

}(jQuery));