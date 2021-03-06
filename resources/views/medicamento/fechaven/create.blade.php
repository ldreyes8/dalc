<div class="wrapper wrapper-content animated fadeInRight ecommerce">
    <div class="ibox-content m-b-sm border-bottom">
        <h4 class="box-title" align="center">Agregar medicamento por vencimiento</h4> 
        <a href="javascript:void(0);" onclick="cargarindex(17);">
            <button class="btn btn-primary btn-md btn-detalle-empleado" title="Listado medicamento caducados"><i class="fa fa-arrow-circle-left"></i></button>
        </a>
        <hr style="border-color:black;"/>
        <div class="panel-body">
            <div class="row">
                
                <div id="rmedi">
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                        <label>Medicamento</label>
                        <input type="text" name="" placeholder="Medicamento a comprar" class="form-control" disabled="" id="medica">
                    </div>

                    <div class="col-lg-2 col-md-4 col-sm-4 col-xs-12">
                        <br>
                        <a href="javascript:void(0);" onclick="cargarbusqueda(9);">
                            <button type="button" class="btn btn-info btn-md" id="brmedi" title="Buscar Medicamento" value=""><i class="fa fa-search"></i></button>
                        </a>
                    </div>
                </div>

                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                    <div class="form-group">
                        <label for="descripcion">Cantidad</label>
                        <input type="number" id="cantidad" class="form-control" placeholder="..." value="0" maxlength="10" onkeypress="return valida(event)" min="0">
                    </div>
                </div>
                
                <div class="col-lg-2 col-md-4 col-sm-4 col-xs-12">
                    <div class="form-group">
                        <br>
                        <button type="button" id="bt_addmv" class="btn btn-info btn-addmv" title="Añadir"><i class="fa fa-plus-circle"></i>&nbsp;Añadir</button>
                    </div>
                </div>

                <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                    <table id="detallevenci" class="table table-striped table-bordered table-hover">
                        <thead style="background-color:#A9D0F5">
                            <tr>
                                <th>Opciones</th>
                                <th>Medicamento</th>
                                <th>cantidad</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div><br></div>
                    <div class="form-group">
                        <button class="btn btn-primary btn-btnGuardarMVencimiento"  type="button" id="btnGuardarMVencimiento" style="display:none;">Guardar</button>
                        <!--<button class="btn btn-danger" type="reset">Cancelar</button>-->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> 


<div class="modal fade" id="erroresModalRequisicion" tabindex="-1" role="dialog" aria-labelledby="Login" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="inputErrorRequisicion"></h4>
            </div>
            <div class="modal-body">
                <ul style="list-style-type:circle" id="erroresContentRequisicion"></ul>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>


    <script src="{{asset('assets/js/medicamento/requisicion.js')}}"></script>
    <script src="{{asset('assets/js/validacion.js')}}"></script>
<!-- Sweet alert -->
    <script src="{{asset('assets/js/plugins/sweetalert/dist/sweetalert2.min.js')}}"></script>
    <script src="{{asset('assets/js/plugins/sweetalert/dist/sweetalert2.js')}}"></script>

<!-- DatePicker -->

    

<script type="text/javascript">
    $('.chosen-select').chosen({width: "100%"});

    $(document).on('click','.btn-btnGuardarMVencimiento',function(e){
    var itemsData=[];
    var urlraiz=$("#url_raiz_proyecto").val();
    var miurl = urlraiz+"/medicamento/vencimiento/store";
        
    $('#detallevenci tr').each(function(){
        var id = $(this).closest('tr').find('input[type="hidden"]').val();
        var cant = $(this).find('td').eq(2).html();
        valor = new Array(id,cant);
        itemsData.push(valor);
    });
        
    var formData = {
        items: itemsData,
    };
        
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        }
    });

    $.ajax({
        type: "POST",
        url: miurl,
        data: formData,
        dataType: 'json',
            
        success: function (data) {
            swal({ 
                title:"Envio correcto",
                text: "Información guardada correctamente",
                type: "success"
            },
            function(){
                cargarindex(17);            
            });     
        },
        error: function (data) {
            var errHTML="";
            if((typeof data.responseJSON != 'undefined')){
                for( var er in data.responseJSON){
                        errHTML+="<li>"+data.responseJSON[er]+"</li>";
                }
            }else{
                errHTML+='<li>Error al borrar el &aacute;rea de atenci&oacute;n.</li>';
            }
            $("#erroresContentEmpleado").html(errHTML); 
            $('#erroresModalEmpleado').modal('show');
        },
    });
});

var cont = 0;
function agregar(){
    idmedicamento =$("#idmedicamento").val(); 
    medicamento =$("#medica").val();
    cantidad = $('#cantidad').val();
    cantexistente = $('#cantexistente').val();

    if(idmedicamento != '' && cantidad != '' && medicamento != '' && cantidad > 0)
    {
        if(cantexistente >= cantidad)
        {
            var item  = '<tr class="even gradeA" id="medicamento'+cont+'">';
            item +='<td><button type="button" class="btn btn-warning" onclick="eliminar('+cont+');">X</button></td>';
            item += '<td><input type="hidden" name="idmedicamento[]" value="'+idmedicamento+'">'+medicamento+'</td>';
            item += '<td>'+cantidad+'</td><tr>';
            cont++;
                
            limpiar();
            evaluar();
            $('#detallevenci').append(item);
        }
        else
        {
            alert("La cantidad requerida no debe ser mayor a la cantidad existente");
        }
    }
    else{
        alert("Error al ingresar el detalle, revise los datos del medicamento")
    }

    function limpiar(){
        $("#idmedicamento").val("");
        $("#medica").val("");
        $("#cantidad").val("");
        $("#cantexistente").val("");
    }
}

$(document).ready(function() {
    $('#bt_addmv').click(function() {
        agregar();
    });
});

function evaluar(){
    if (cont>0){
        $("#btnGuardarMVencimiento").show();
    }
    else{
        $("#btnGuardarMVencimiento").hide();
    }
}
</script>




