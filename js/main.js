jQuery(document).on('submit','#formLogin', function(event) {

	event.preventDefault();

		jQuery.ajax({

			url: 'includes/login.php',
			type: 'POST',
			dataType: 'JSON',
			data: $(this).serialize(),
			beforeSend: function() {

				$('#botonlogin').val('Validando...');
			}
		})
		.done(function(respuesta) {

			console.log(respuesta);
			if(!respuesta.error){

				if(respuesta.tipo == 'empleado'){

					location.href ='view/empleado/';
				}

				else if(respuesta.tipo == 'jefe') {

					location.href = 'view/jefe/';
				}				

				else if(respuesta.tipo == 'root') {

					location.href = 'view/root/';
				}
			}

			else {

				$('.error').slideDown('slow');
				setTimeout(function() {
					$('.error').slideUp('slow');
				},2000);
				$('#botonlogin').val('Validando...');
			}
		})
		.fail(function(resp) {

			console.log(resp.responseText);
		})
		.always(function() {

			console.log("complete");
		});
});