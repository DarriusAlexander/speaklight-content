(function($,api) {
	'use strict';

	var DC={},
            DynamicCache=null,
            CacheRequest = {},
            fieldName=tbpDynamic.field_name,
            hidden;
        
        var getData = function (callback) {
                if ( DynamicCache ===null ) {
                    if(tbpDynamic['type']){
                        document.body.classList.add('tbp_template_type_'+tbpDynamic['type']);
                        window.top.document.body.classList.add('tbp_template_type_'+tbpDynamic['type']);
                    }
                    var name='tbp_dc',
                        key=Themify.hash(tbpDynamic.v+Object.keys(tbpDynamic.items)),
                        writeStorage=function(value){
                            try{
                                sessionStorage.setItem(name,JSON.stringify({'v':value,'h':key}));
                            }
                            catch(e){
                                return null;
                            }
                        },
                        readStorage=function () {
                            if(themifyBuilder.debug){
                                return null;
                            }
                            try{
                                var result=sessionStorage.getItem(name);
                                if(result){
                                    result = JSON.parse(result);
                                    if(result['h']===key && result['v']){
                                        return result['v'];
                                    }
                                }
                            }
                            catch(e){
                                return null;
                            }
                            return null;
                        };
                    DynamicCache = readStorage();
                    if(DynamicCache===null){
                        $.ajax({
                            type: 'POST',
                            url: themifyBuilder.ajaxurl,
                            dataType: 'json',
                            data: {
                                action: 'tpb_get_dynamic_content_fields',
                                tb_load_nonce: themifyBuilder.tb_load_nonce
                            },
                            error:function(){
                                DynamicCache=null;  
                            },
                            success: function ( data ) {
                                DynamicCache = data;
                                writeStorage(data);
                                if(callback){
                                    callback();
                                }
                            }
                        });
                    }
                } else if(callback){
                    callback();
                }
        },
        getPreviewVal = function(vals,callback){
            // get preview
            var after='',
                before='',
                result=function(v){
                    if(before!==undefined || after!==undefined){
                        if(!v){
                            v='';
                        }
                        if(before!==undefined){
                                v=before+v;
                        }
                        if(after!==undefined){
                                v+=after;
                        }
                    }
                    if(callback){
                        callback(v);
                    }
                },
                req;
            if(typeof vals!=='string'){
                if(!vals || vals['item']===undefined){
                    return;
                }
                req = $.extend(true,{},vals);
                after =req['text_after'],
                before=req['text_before'];
                delete req['text_before'];
                delete req['text_after'];
                delete req['o'];
                req = JSON.stringify(req);
            }
            else{
                req=vals;
            }
            var postId;
            if(api.Forms.LayoutPart.id && document.body.classList.contains('tbp_app_is_edit')){
                    postId =api.Instances.Builder[api.builderIndex].el.parentNode.id;
                    if(postId){
                            postId = postId.split('-')[1];
                    }
            }
            if(!postId){
                    postId=typeof tbp_local!=='undefined' && tbp_local['id']!==undefined && !tbp_local['isArchive']?tbp_local['id']:themifyBuilder.post_ID;
            }
            var key = Themify.hash(req+postId);
            if(CacheRequest[key]===undefined){
                $.ajax({
                        type: 'POST',
                        url: themifyBuilder.ajaxurl,
                        dataType: 'json',
                        data: {
                                action: 'tpb_get_dynamic_content_preview',
                                tb_load_nonce: themifyBuilder.tb_load_nonce,
                                pid : postId,
                                values : req
                        },
                        success : function( data ) {
                                CacheRequest[key] = data['error']?data['error']:(data['value']==='' || data['value']==='false' ? null : data['value']);
                                result(CacheRequest[key]);
                        }
                } );
            }
            else{
                result(CacheRequest[key]);
            }
        },
        createOptions=function(type,values){
            var oldVals= ThemifyConstructor.values,
                options_wrap=document.createElement('div'),
                Options =Object.values($.extend(true,{}, DynamicCache)),
                dynamic=tbpDynamic.items;
                options_wrap.className='tbp_dynamic_content_options';
                for(var i=Options.length-1;i>-1;--i){
                        if(Options[i].id==='item'){
                            var group = Options[i].options;
                            for(var j in group){
                                for(var k in group[j]['options']){
                                    if(dynamic[ k ]!==undefined && dynamic[ k ]['type'].indexOf(type)===-1 ) {
                                        delete group[j]['options'][k];
                                    }
                                }
                                if(Object.keys(group[j]['options']).length===0){
                                    delete group[j];
                                }
                            }  
                            break;
                        }
                    }
            if(values===undefined){
                values={};
            }
            ThemifyConstructor.values = values;
            var form= ThemifyConstructor.create(Options),
                opt=form.querySelectorAll('.tb_lb_option');
            // prevent Builder from saving these fields individually
            for(i=opt.length-1;i>-1;--i){
                opt[i].classList.remove('tb_lb_option');
                opt[i].classList.remove('tb_lb_option_child');
            }
            ThemifyConstructor.values=oldVals;
            opt=Options=dynamic=oldVals=null;
            options_wrap.appendChild(form);
            return options_wrap;
        },
        EnableDc=function(el,init){
            
            var field = el.parentNode.closest('.tb_field'),
                pid=getRepeatId(field),
                type = getType( field ),
                parent=field.getElementsByClassName('tb_input')[0],
                values={};
                if(parent!==undefined && parent.parentNode.classList.contains('tb_has_dc')){
                    field = parent;
                    parent=null;
                }
                var id = getId(field,type);
                if(pid!==null){
                    if(DC[pid]!==undefined){
                        var index=getRepeatIndex(field);
                        if(DC[pid][index]!==undefined){
                            values=DC[pid][index];
                        }
                    }
                }
                else{
                    values = DC;
                }
                if(init===true){
                    if(values[id]!==undefined){
                        el.checked=true;
                    }
                    else{
                        return;
                    }
                }
                if(el.checked===true){
                    field.classList.add('tbp_dc_active');
                    var placeholder=field.getElementsByClassName( 'tbp_dc_input' )[0];
                    if (placeholder===undefined ) {
                        getData( function() {
                           var  wrap=document.createElement('div');
                                placeholder=document.createElement('input');
                                wrap.className='tbp_dc_wrap';
                                placeholder.className='tbp_dc_input xlarge';
                                placeholder.type='text';
                                placeholder.setAttribute('readonly',true);
                                var onTypeChange= function(el){
                                    var dcWrap = el.closest('.tbp_dc_wrap'),
                                        itemType=dcWrap.querySelector('#item'),
                                        v = itemType.value,
                                        items = itemType.closest('.tb_field').nextElementSibling.children,
                                        cl = 'field_'+v,
                                        blocks =[],
                                        generalCl='field_general_'+type;
                                        placeholder.value=itemType.options[itemType.selectedIndex].text;
                                        blocks.push(itemType.parentNode);
                                    for(var i=items.length-1;i>-1;--i){
                                        if(v!=='' && (items[i].classList.contains(cl) || items[i].classList.contains(generalCl))){
                                            items[i].style['display']='block';
                                            blocks.push(items[i]);
                                        }
                                        else{
                                            items[i].style['display']='';
                                        }
                                    }
                                    ThemifyConstructor.callbacks();
                                    if(!v){
                                        blocks=null;
                                    }
                                    var vals= update_value( id, blocks,dcWrap );
                                    blocks=null;
                                    getPreviewVal(vals,function(res){

                                            /* fallback value in preview */
                                            if ( null === res ) {
                                                if ( type === 'image' ) {
                                                    res = tbpDynamic.placeholder_image;
                                                } else {
                                                    res = '{' + vals['item'] + '}';
                                                }
                                            }

                                        var item = getField(itemType,id);
                                        if(item!==null){
                                            item.value=res;
                                            var obj=null;
                                            if(type==='wp_editor'){
                                                var obj =tinymce.get( item.id );
                                                if(obj){
                                                    obj.setContent( String( res ) );
                                                    obj.fire( 'change' );
                                                }
                                            }
                                            if(!obj){
                                                Themify.triggerEvent(item,'change');
                                                if( type!=='image' && item.nodeName!=='SELECT'){
                                                    Themify.triggerEvent(item,'keyup');
                                                }
                                            }
                                        }
                                    });
                                };
                                placeholder.addEventListener('click',function(e){
                                    e.preventDefault();
                                    e.stopImmediatePropagation();
                                    var options_wrap = this.nextElementSibling;
                                    if(options_wrap===null){
                                        options_wrap = createOptions(type,values[id]);
                                        $(options_wrap).on( 'change.dc_preview', ':input',function(e){
                                            e.stopPropagation();
                                            onTypeChange(e.target);
                                        });
                                        this.parentNode.appendChild(options_wrap);
                                    }
                                    var isVisible=options_wrap.style['display']!=='block',
                                        items =ThemifyBuilderCommon.Lightbox.$lightbox[0].getElementsByClassName('tbp_dynamic_content_options');
                                    for(var i=items.length-1;i>-1;--i){
                                        items[i].style['display']='';
                                    }
                                    if(isVisible===true){
                                        var Click = function(e){
                                            if(e.target.closest('.tbp_dc_wrap')===null){
                                                document.removeEventListener('mousedown',Click,{passive:true});
                                                if(api.mode==='visual'){
                                                    window.top.document.removeEventListener('mousedown',Click,{passive:true});
                                                }
                                                options_wrap.style['display']='';
                                            }
                                            if(api.mode==='visual'){
                                                $(document).triggerHandler('mouseup');
                                            }
                                        };
                                        document.addEventListener('mousedown',Click,{passive:true});
                                        if(api.mode==='visual'){
                                            window.top.document.addEventListener('mousedown',Click,{passive:true});
                                        }
                                        onTypeChange(this);
                                        options_wrap.style['display']='block';
                                    }
                                    else{
                                        options_wrap.style['display']='';
                                    }
                                });
                                wrap.appendChild(placeholder);
                                if(values[ id ]!==undefined){
                                    var value= values[ id ]['item'],
                                        opt = DynamicCache[0]['options'];
                                    for(var i in opt){
                                        if(opt[i]['options'][value]!==undefined){
                                            placeholder.value=opt[i]['options'][value];
                                            break
                                        }
                                    }
                                    if(type==='image' && ThemifyConstructor.clicked==='styling'){
                                        var imgOptions=field.closest('.tb_tab').getElementsByClassName('tb_image_options');
                                        for(i=imgOptions.length-1;i>-1;--i){
                                            imgOptions[i].classList.remove('_tb_hide_binding');
                                        }
                                    }
                                    else{
                                        ThemifyConstructor.callbacks();
                                    }
                                }
                                field.appendChild(wrap);
                        } );
                        if(init===undefined){
                            setOrigValue(field,id,type);
                        }
                    }
                    else if(init===undefined){
                        setOrigValue(field,id,type);
                        if(values[id]!==undefined){
                            placeholder.click();
                        }
                        toggleStylesheet(false);
                    }
                }
                else if(init===undefined){
                    revertOrigValue(field,id,type);
                    field.classList.remove('tbp_dc_active');
                    update_value( id, null,field );
                    toggleStylesheet(true);
                }
            
        },
        getRepeatIndex=function(el){
            return $(el.closest('.tb_repeatable_field')).index();
        },
        getRepeatId=function(el){
            var item= el.parentNode.closest('.tb_row_js_wrapper');
            if(item!==null){
                return item.getAttribute('id');
            }
            return null;
        },
        toggleStylesheet=function(disable){
            if(api.mode==='visual' && ThemifyConstructor.clicked==='styling'){
                var el = api.liveStylingInstance.$liveStyledElmt[0].closest('.tb_active_layout_part');
                    if(el===null){
                        el=api.liveStylingInstance.$liveStyledElmt[0];
                    }
                    var styles=el.getElementsByClassName('tbp_dc_styles');
                    for(var i=styles.length-1;i>-1;--i){
                        styles[i].sheet.disabled =disable;
                    }
            }  
        },
        getId=function(el,type){
            var cl=type==='image'?'tb_uploader_input':(el.parentNode.closest('.tb_repeatable_field_content')!==null?'tb_lb_option_child':'tb_lb_option'),
                item = el.getElementsByClassName(cl)[0],
                id;
            if(item!==undefined){
                id=item.getAttribute('data-input-id');
            }
            if(!id){
                id=item.getAttribute('id');
            }
            return id.trim();
        },
	update_value=function ( key, val,item ) {
            var dc,
                index=null,
                pid=getRepeatId(item);
            if(pid!==null){
                index=getRepeatIndex(item);
            }
            if ( val === null ) {
                dc=JSON.parse(hidden.value);
                if(!dc){
                    dc={};
                }
                if(index!==null){
                    var update = false;
                    if(dc[pid]!==undefined){
                        if(key===null){
                            if(dc[pid][index]!==undefined){
                                update = true;
                                delete DC[pid][index];
                                delete dc[pid][index];
                            }
                        }
                        else if( dc[pid][index]!==undefined && dc[pid][index][key]!==undefined){
                            update = true;
                            delete dc[pid][index][key];
                            var len=Object.keys(dc[pid][index]).length;
                            if(len===0 || (len===1 && dc[pid][index]['o']!==undefined)){
                                update = true;
                                delete DC[pid][index];
                                delete dc[pid][index];
                            }
                        }
                        else{
                            return;
                        }
                        if(Object.keys(dc[pid]).length===0){
                            update = true;
                            delete DC[pid];
                            delete dc[pid];
                        }
                    }
                    if(update===false){
                        return;
                    }
                }
                else{
                    if(dc[key]!==undefined){
                        delete dc[ key ];
                    }
                    else{
                        return;
                    } 
                }
            } 
            else {
                var orig;
                if(index!==null){
                    if(DC[ pid ]===undefined){
                        DC[ pid ]={};
                    }
                    if(DC[pid][index]===undefined){
                        DC[pid][index]={};
                    }
                    if(DC[pid][index][key]===undefined){
                        DC[pid][index][key]={};
                    }
                    orig =DC[pid][index][key]['o']!==undefined? DC[pid][index][key]['o']:null;
                }
                else{
                    if(DC[ key ]===undefined){
                        DC[ key ]={};
                    }
                    orig =DC[key]['o']!==undefined? DC[key]['o']:null;
                }
                if(Array.isArray(val)){
                    var values={};
                    for(var i=val.length-1;i>-1;--i){
                        var items = val[i].querySelectorAll('input,textarea,select');
                        for(var j=items.length-1;j>-1;--j){
                            var v =items[j].value;
                            if(v!=='' && !items[j].parentNode.parentNode.classList.contains('_tb_hide_binding')){
                                values[items[j].id]=v;
                            }
                        }
                    }
                    val=api.Utils.clear(values);
                }
                if(index!==null){
                    DC[pid][index][key] = val;
                    if(orig!==null){
                        DC[pid][index][key]['o'] = orig;
                    }
                }
                else{
                    DC[ key ] = val;
                    if(orig!==null){
                        DC[ key ]['o'] = orig;
                    }
                }
                dc=DC;
            }
            hidden.value=JSON.stringify( dc );
            return val;
	},
        
	/**
	 * Get original field
	 *
	 * @return dom element
	 */
        getField=function(field,id){
            var item = field.closest('.tb_repeatable_field_content');
            if(item!==null){
                item=item.querySelector('.tb_lb_option_child[data-input-id="'+id+'"]');
            }
            else{
                item = ThemifyBuilderCommon.Lightbox.$lightbox[0].querySelector('#'+id);
            }
            return item;
        },
	/**
	 * Set value to original field
	 *
	 * @return mixed
	 */
	setOrigValue=function ( field,id,type ) {
            var value=null,
                index=null,
                _id=id;
            if(type==='wp_editor'){
                var obj = tinymce.get( id );
                if(!obj){
                    var item = getField(field,id);
                        obj = tinymce.get( item.id );
                }
                if(obj){
                    value=obj.getContent();
                }
            }
            else{
                var item = getField(field,id);
                if(item!==null){
                    value=item.value;
                }
            }
            var pid=getRepeatId(field);
            if(pid!==null){
                index=getRepeatIndex(field);
                _id = pid;
            }
            if(value!==null && value!==''){
                if(DC[_id]===undefined){
                    DC[_id]={};
                }
                if(index!==null){
                    if(DC[_id][index]===undefined){
                        DC[_id][index] = {};
                    }
                    if(DC[_id][index][id]===undefined){
                        DC[_id][index][id]={};
                    }
                    DC[_id][index][id]['o'] =value; 
                }
                else{
                    DC[_id]['o']=value;
                }
            }
            else if(DC[_id]!==undefined){
                if(index!==null){
                    if(DC[_id][index]!==undefined && DC[_id][index][id]!==undefined){
                        delete DC[_id][index][id]['o'];
                    }
                }
                else{
                    delete DC[_id]['o'];
                }
            }
            hidden.value=JSON.stringify( DC );
            return value;
	},
        /**
	 * Revert value to original field
	 *
	 * @return void
	 */
        revertOrigValue=function(field,id,type){
            var pid=getRepeatId(field),
                v='';
            if(pid!==null){
                if(DC[pid]!==undefined){
                    var index=getRepeatIndex(field);
                    if(DC[pid][index]!==undefined && DC[pid][index][id]!==undefined && DC[pid][index][id]['o']!==undefined){
                        v=DC[pid][index][id]['o'];
                    }
                }
            }
            else if(DC[id]!==undefined && DC[id]['o']!==undefined){
                v=DC[id]['o'];
            }
            if(type==='wp_editor'){
                var obj =tinymce.get( id );
                if(!obj){
                    var item = getField(field,id);
                        obj = tinymce.get( item.id );
                }
                if(obj){
                    obj.setContent( String( v ) );
                    obj.fire( 'change' );
                }
            }
            else{
                var item = getField(field,id);
                if(item!==null){
                    item.value=v;
                    Themify.triggerEvent(item,'change');
                    if( type!=='image' && item.nodeName!=='SELECT'){
                        Themify.triggerEvent(item,'keyup');
                    }
                }
            }
        },
	/**
	 * Get a div.tb_field element and returns the element type
	 *
	 * @return string
	 */
	getType=function ( field ) {
		var type = field.getAttribute( 'data-type' );
		/* imageGradient field type is used in Styling, functions similarly as "image" */
		if ( type === 'imageGradient' ) {
                    type = 'image';
		}
		return type;
	},
	addSwitch = function ( container ) {
                var exlclude=tbpDynamic.excludes,
                    dl=tbpDynamic.d_label,
                    found=false,
                    types = [ 'date', 'text', 'image', 'audio', 'icon', 'textarea', 'number', 'range', 'address', 'wp_editor', 'imageGradient', 'url', 'gallery', 'custom_css' ];
                    for(var i=types.length-1;i>-1;--i){
                        var items = container.querySelectorAll('.tb_field[data-type="' + types[i] + '"]');
                        for(var j=items.length-1;j>-1;--j){
                            if ( ! items[j].classList.contains( 'tb_has_dc' ) && ! items[j].classList.contains( 'tb_disable_dc' ) ) {
                                found=false;
                                /* certain options should not have DC enabled */
                                for(var k=exlclude.length-1;k>-1;--k){
                                    if(items[j].classList.contains(exlclude[k])){
                                        found=true;
                                        break;
                                    }
                                }
                                if(found===false){
                                    items[j].className+=' tb_has_dc';
                                    var label = document.createElement('label'),
                                        input=document.createElement('input'),
                                        div=document.createElement('div');
                                    label.className='tpb_dc_toggle switch-wrapper';
                                    input.type='checkbox';
                                    input.className='toggle_switch';
                                    div.className='switch_label';
                                    div.setAttribute('data-on',dl);
                                    div.setAttribute('data-off',dl);
                                    input.addEventListener('change',function(e){
                                        e.stopPropagation();
                                        EnableDc(this);
                                    },{passive:true});
                                    label.appendChild(input);
                                    label.appendChild(div);
                                    items[j].insertBefore(label, items[j].firstChild);
                                    EnableDc(input,true);
                                }
                            }
                        }
                    }
	};
        $(window).one('load',function(){
                if(api.mode!=='visual' || Themify.is_builder_loaded===true){
                    setTimeout(getData,1500);
                }
                else{
                    window.top.Themify.body.one('themify_builder_ready', function (e) {
                       setTimeout(getData,2500);
                    });
                }
                $( document ).on( 'tb_repeatable_add_new tb_repeatable_duplicate tb_repeatable_delete', function( e ) {
                        if(e.type==='tb_repeatable_delete'){
                            update_value(null,null,e.detail[0]);
                        }
                        else{
                            addSwitch( e.detail[0] );
                        }
                } ).on( 'tb_editing_module tb_editing_row tb_editing_column tb_editing_subrow', function( e ) {
                        var builder = ThemifyConstructor,
                            container = ThemifyBuilderCommon.Lightbox.$lightbox[0];
                            hidden = builder.hidden.render({
                                        'type' : 'hidden',
                                        'class':'exclude-from-reset-field',
                                        'responsive':false,
                                        'control':false,
                                        'id'   : fieldName
                                    },builder);
                            // save & store previously saved values
                            if(builder.values[ fieldName ]!==undefined){
                                try{
                                    DC = typeof builder.values[ fieldName ]==='string'?JSON.parse( builder.values[ fieldName ] ):$.extend(true,{},builder.values[ fieldName ]);
                                    hidden.value=JSON.stringify( DC ) ;
                                }
                                catch(e){
                                    DC={};
                                }
                            }
                            else{
                                DC={};
                            }
                        container.getElementsByClassName( 'tb_options_tab_content' )[0].appendChild(hidden);
                        builder=null;
                        addSwitch(container);
                } );
                Themify.body.on( 'themify_builder_tabsactive tb_options_expand', function( e, id, container ) {
                    if(api.activeModel!==null){
                        var clicked=ThemifyConstructor.clicked;
                        setTimeout(function(){
                            clicked=ThemifyConstructor.clicked;
                            if(clicked!=='animation' && clicked!=='visibility'){
                                if(e.type==='tb_options_expand'){
                                    container = id;
                                    id=null;
                                }
                                if(clicked!=='styling' || id===null || id.indexOf('_h')===-1){
                                    addSwitch( container );
                                }
                            }
                        },clicked==='setting'?0:50);
                    }
                } );
        });

})(jQuery,tb_app);