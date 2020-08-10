"use strict;"

var form_box_temp='';

class mShowClass
{
    constructor()
    {

        this.template = `
                        <style>
                            #_dg-fade
                            {
                                display: none; 
                                position: absolute; 
                                left: 0px;
                                top:0px; 
                                background-color: white; 
                                width: 100%;
                                height: 100%; 
                                opacity: 0.8;
                                z-index: 100;
                            }
                            ._box-dialog
                            {
                                position: absolute;
                                width: 600px;
                                background-color:white; 
                                border-radius: 0px 0px 5px 5px;
                                border: 1px solid #c3c9d1;
                                box-shadow: 0px 0px 15px lightblue;
                            }
                            ._b-error
                            {
                                background: url('../img/alerta_w_30.png') 10px 15px no-repeat;
                                color: white;
                                background-color: red;
                            }
                            ._b-correcto
                            {
                                background: url('../img/correcto_w_30.png') 10px 15px no-repeat;
                                color:  white;
                                background-color: #0074e3/*#0174DF*/;
                            }
                            ._b-alerta
                            {
                                background: url('../img/alerta_w_30.png') 10px 15px no-repeat;
                                color: white;
                                background-color: #DF7401;
                            }
                            ._b-info
                            {
                                background: url('../img/info_30.png') 10px 15px no-repeat;
                                color:  #0074e3;
                                background-color: white;
                            }
                            ._box-tit
                            {
                                height: 60px;
                                text-align: center;
                                font-weight: bold;
                                display: flex;
                                padding-left: 55px;
                                align-items: center;
                                font-size: 10pt;
                            }
                            ._box-bdy
                            {
                                margin: 20px;
                                margin-bottom: 0px;
                                color: gray;
                                font-size: 10pt;
                                #text-align: justify;
                                overflow: auto;
                            }
                            ._ico-tit
                            {
                                height: 50px;   
                                width: 100px;   
                                text-align: center;
                                float: left;
                            }
                            ._box-opt
                            {
                                padding: 20px;
                                overflow: auto;
                            }
                            #_form_primario{
                                display:block!important;
                            }
                        </style>

                        <div id = '_dg-fade'></div>
                            
                        <div id = '_dg-dialog' style='position: absolute; left: 0px;top:0px; width: 100%;height: 100%; display: none; align-items: center; justify-content: center;'>
                                
                            <div class = '_box-dialog'>

                                <div id='_dg-tit' class = '_box-tit'></div>
                                
                                <!-- <div id='_dg-ico' class = 'ico-tit'></div> -->

                                <div id='_dg-bdy' class = '_box-bdy'></div>

                                <div id='_dg-opt' class = '_box-opt'>
                                
                                    <input id='_btn_cancel' style='float: left;width: 90px;' class='btn btn-sm btn-danger' type='button' value='Cancelar'>
                                
                                    <input id='_btn_ok'  style='float: right;width: 150px;' class='btn btn-sm btn-info' type='button' value='Aceptar' onclick='function(){}'>
                                
                                </div>

                            </div>

                        </div>`;

        let element = document.createElement('TEMPLATE');

        element.innerHTML = this.template;
        
        element.setAttribute ( 'id' , '_x_template_mshow');

        document.body.appendChild(element);

        this.cuadro_dialogo = document.querySelector('#_x_template_mshow').content.cloneNode(true);

        document.body.appendChild(this.cuadro_dialogo);

    }

    mModal( contenedor, form , titulo, tipo , titulo_boton_primario, titulo_boton_secundario ,funcion_ok='', funcion_cancelar = '', width = '', fondo_activo = '')
    {

        switch (tipo)
        {

            case 0: this.clase = '_b-error'; break;

            case 1: this.clase = '_b-correcto'; break;

            case 2: this.clase = '_b-alerta'; break;

            case 3: this.clase = '_b-info'; break;

        }

        $('#_dg-tit').removeClass();

        $('#_dg-tit').addClass( '_box-tit ' + this.clase);

        $('#_dg-tit').html(titulo);
        
        $('#_dg-bdy').html('');

        if ( width )
        {

            $('._box-dialog').css( 'width' , width );
        
        }

        // let form_box = $(form).clone();

        // form_box_temp = $(form).html();

        // $(form_box).attr('id', '_form_primario');
                
        // $('#_form_primario').show();

        // $(form).remove();

        // $('#_dg-bdy').html(form_box);

    $(form).appendTo('#_dg-bdy');


        $('#wallblock').show();

        $('#_dg-dialog').css('z-index','900001');

        $('#_dg-dialog').css('display','flex');

        // $('#_dg-dialog').show();

        $('#_dg-fade').css('z-index','900000');

        // $('#_dg-fade').removeClass('fadebox-val');

        $('#_dg-fade').show();

        $('._box-dialog').fadeIn(300);

        document.querySelector('#_btn_ok').value = titulo_boton_primario;

        document.querySelector('#_btn_ok').style.display = 'block';

        document.querySelector('#_btn_cancel').value = titulo_boton_secundario;

        if ( titulo_boton_secundario )
        {

            document.querySelector('#_btn_cancel').style.display = 'block';

        }
        else
        {

            document.querySelector('#_btn_cancel').style.display = 'none';

        }

        document.querySelector('#_x_template_mshow').setAttribute('hidden' , false);

        document.querySelector('#_btn_ok').setAttribute('onclick' , funcion_ok );
        
        document.querySelector('#_btn_cancel').setAttribute('onclick' , 'dialogo.unMShow(' + fondo_activo + ');$("' + form + '").appendTo("' + contenedor + '");' + funcion_cancelar );


    }

    unModal(contenedor,form)
    {

        $(form).appendTo(contenedor);
        
        this.unMShow('');

    }

    mShow( parametro , funcion_ok,  funcion_cancelar = '', fondo_activo = '')
    {

        this.titulo  =   parametro.titulo;
        
        this.mensaje =   parametro.mensaje;
        
        this.tipo    =   parametro.tipo;
        
        this.titulo_boton_primario   =   parametro.boton1;

        this.titulo_boton_secundario =   parametro.boton2;

        document.querySelector('#_x_template_mshow').setAttribute('hidden' , false);

        document.querySelector('#_btn_ok').setAttribute('onclick' , 'dialogo.unMShow(' + fondo_activo + ');' +  funcion_ok );
        
        document.querySelector('#_btn_cancel').setAttribute('onclick' , 'dialogo.unMShow(' + fondo_activo + ');' +  funcion_cancelar );

        if ( this.titulo_boton_primario )
        {
        
            document.querySelector('#_btn_ok').value = this.titulo_boton_primario;

            document.querySelector('#_btn_ok').style.display = 'block';

            document.querySelector('#_btn_cancel').style.width='110px';
            
        }
        else
        {
            
            document.querySelector('#_btn_ok').style.display = 'none';

            document.querySelector('#_btn_cancel').style.width='100%';

        }
        
        if ( this.titulo_boton_secundario )
        {
        
            document.querySelector('#_btn_cancel').value = this.titulo_boton_secundario;

            document.querySelector('#_btn_cancel').style.display = 'block';

            document.querySelector('#_btn_ok').style.width='110px';
            
        }
        else
        {
            
            document.querySelector('#_btn_cancel').style.display = 'none';

            document.querySelector('#_btn_ok').style.width='100%';

        }

        switch (this.tipo)
        {

            case 0: this.clase = '_b-error'; break;

            case 1: this.clase = '_b-correcto'; break;

            case 2: this.clase = '_b-alerta'; break;

            case 3: this.clase = '_b-info'; break;

        }

        $('#wallblock').show();

        $('#_dg-ico').html('');

        $('#_dg-tit').removeClass();

        $('#_dg-tit').addClass( '_box-tit ' + this.clase);
        
        $('#_dg-tit').html(this.titulo);

        $('#_dg-bdy').html(this.mensaje);

        $('#_dg-dialog').css('z-index','900001');

        $('#_dg-dialog').css('display','flex');

        // $('#_dg-dialog').show();

        $('#_dg-fade').css('z-index','900000');

        // $('#_dg-fade').removeClass('fadebox-val');

        $('#_dg-fade').show();

        $('._box-dialog').fadeIn(300);
        
    };

    unMShow( opt )
    {

        $('#wallblock').hide();

        $('#_dg-dialog').hide(0);
        // $('#dialog')._dg-fadeOut(100);

        if ( !opt &&  !opt == undefined )
        {

            $('#_dg-fade').css('z-index','900');

        }
        else
        {

            $('#_dg-fade').css('z-index','900');

            $('#_dg-fade').hide(0);

        }
        // $('#_dg-fade').fadeOut(200);
    }

}
