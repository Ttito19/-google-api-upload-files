<html>
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<style>
			

			.percent {
			  position: absolute; /*fija su opocision a partir del elemento padre  */
				display: inline-block;  
				 color: #1b1b1b; 
				 font-weight: bold; /* aplicarle  en negrita */ 
				top: 20px;
				 left: 50%;;   
				/* text-align:center; */
				  margin-top: -9px; 
				 margin-left: -20px;  
				-webkit-border-radius: 4px
			}
			#progressDivId{

				  position:relative;  
				
			}

			.progress{
				width: 50%;
				
			}
			.main-content{
background:#ebebeb7e;
			}
			
		</style>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
	</head>
	<script>
			$(document).on('change',"#input-file-id",function(){
				var names = [];
    for (var i = 0; i < $(this).get(0).files.length; ++i) {
		// if(i===0){
			names.push($(this).get(0).files[i].name);
		// }else{
		// 	names.push("|"+$(this).get(0).files[i].name);
		// }
      
    }
				$("#input-label-id").html(names.length + ' archivos seleccionados')
			})
		</script>
	<body>

		<div class="col-12 col-sm-11 col-md-12 col-lg-12 m-auto small-top-space" >
			<div class="custom-control custom-checkbox">
				<input type="checkbox" class="custom-control-input" id="chk-subir">
			<label class="custom-control-label" for="chk-subir">Subir varias informaciones</label>
			</div>
		</div>




		<div class="col-12 col-sm-11 col-md-10 col-lg-12 mt-2 mx-auto" id="form-upload" style="display:none">
		<div class="d-flex flex-column main-content border px-3 pt-2 rounded">
			<form  method="POST"  id="form"  class="small-top-space" enctype="multipart/form-data">
			<div class="form-row">
			<legend>Subir archivo mediante Google Drive API</legend>

		<div class="form-group col-lg-10">		
			<div  class="input-group"> 
			  <div class="custom-file">
			    <label class="custom-file-label" for="input-file-id" id="input-label-id">Escoger Archivo</label>
			    <input type="file" class="custom-file-input"  name="file[]" id="input-file-id" multiple="multiple"  >		   
			  </div>
		  </div>		
		</div>
		
			<div class="form-group col-lg-2">
			<button class="btn btn-success ripple ancho-100 m-auto" id="btn-subir">Subir Archivo</button>
			</div>

		<div class="form-group col-lg-12">
			<div class="progress" id="progressDivId" style="margin: 0;height: 40px" >
				<div class="progress-bar progress-bar-striped active" id="progressBar"    role="progressbar" aria-valuenow="25"   >				    
					<div class='percent' id='percent'>
				0%
					</div>
				</div>
				
			</div>
		</div>
		
			
	
			<div id="idArchivo" style="display:none;"></div>
			<!-- <div id='outputImage'></div> -->

		</div>
		</form>
		
		</div>
		</div>
		<div id="loader-icon" style="display:none;"><img src="loader.gif" /></div>
	</body>
</html>
<script>
//subir los archivos mediante ajax
$(document).ready(function(){
	$('#form').submit(function(event){		
		if($('#input-file-id').val()){
			event.preventDefault();
			$('#loader-icon').show();
			$('#idArchivo').hide();
			$(this).ajaxSubmit({
				target:'#idArchivo',
				url: 'POST/upload.php',
				beforeSubmit:function(){

					// $("#outputImage").hide();
    	        	   if($("#uploadImage").val() == "") {
    	        		//    $("#outputImage").show();
    	        		//    $("#outputImage").html("<div class='error'>Elige un archivo para subir.</div>");
                    return false; 
                }   	          
    	            var percentValue = '0%';

    	            $('#progressBar').width(percentValue);
    	            $('#percent').html(percentValue);
				},
				uploadProgress:function(event,position,total,percentageComplete)
				{
					var percentValue = percentageComplete + '%';
				$('#progressBar').animate({
			
					 width: '' + percentValue + ''
				},{
					duration: 2500,
    	                easing: "linear",
    	                step: function (x) {
                        percentText = Math.round(x * 100 / percentageComplete);
    	                    $("#percent").text(percentText + "%");
                        if(percentText == "100") {
                        	//    $("#outputImage").show();
                        }
    	                }
				});
			},
			error: function (response, status, e) {
    	            alert('Error');
    	        },
				
    	        
				success:function(){
					$('#loader-icon').hide(); //ocultar el gif
					$('#idArchivo').show();//mostrar el codigo de la imagen
				},
				resetForm: true
			});
		}
		return false;
	});
});

//ocultar y mostrar el formulario
$(document).ready(function(){
	 $("#form-upload").css("display:block");
})

$(document).on("click","#chk-subir",function(){
$("#form-upload").removeAttr("hidden");
if($(this).prop("checked")==true){
	
	$("#form-upload").show("slow");

}else{
	$("#form-upload").hide("slow");
}
// console.log($(this).prop("checked"));

})
</script>




<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<!--<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>-->
<script src="http://malsup.github.com/jquery.form.js"></script> 
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>


