 $(document).ready(function(){


 	var longuer_minimal_text_input = 2;

 	var max_size_image = 2000; //In KB
 	
    $('#newProduct').modal();

    $('select').formSelect();



    //Select New image
    $('.addPic').click(function () {
    	
    	$('#ImageProduct').click()
    })


    //We record the for to add new product
    $('.record').click(function () { 
    	
    	//We verify all field
    	if(verifyEntryOnFormToAddNewProduct($('#Productname').val()) && verifyEntryOnFormToAddNewProduct($('#Productprix').val()) && $('#Productcategorie').val()!='No Category' && $('#Productcategorie').val()!='') {

    		//We verifie if there is a picture selected
    		if ($('#ImageProduct').get(0).files.length === 0) {
			    
			    notification("Vous n'avez pas choisi d'image pour illustrer le produit")
			}else{ alert(Math.round($('#ImageProduct').get(0).files[0].size/1024))

				if(Math.round($('#ImageProduct').get(0).files[0].size/1024)<=max_size_image){

					//We add thi to the database
					uploadThisPicture();
				}else{
					notification("Cette image pèse plus de 2 MB")
				}
			}
    	}
    })


    function verifyEntryOnFormToAddNewProduct(field) {
    	
    	if(field.length>longuer_minimal_text_input){

    		return true; 
    	}else{
    		notification('Texte trop court. Verifiez bien le formulaire')
    		return false;
    	}
    }


    function uploadThisPicture() {

    	//We show Progress bar for upload
    	$('.addNewImage').fadeIn();
    	$('.determinate').css('width', '0%');
    	
    	var formData = new FormData();
        formData.append('ImageProduct', $('#ImageProduct')[0].files[0]);
        formData.append('ProductName',$('#Productname').val());
        formData.append('ProductPrice',$('#Productprix').val());
        formData.append('ProductPromoPrice',$('#Productpromotion').val());
        formData.append('ProductCategory',$('#Productcategorie').val());
        
        $.ajax({
            url: $('.hider').attr('site_url')+$('.hider').attr('url_add_new_product') ,
            data: formData,
            processData: false,
            contentType: false,
            type: 'POST',
            // this part is progress bar
            xhr: function () {
                var xhr = new window.XMLHttpRequest();
                xhr.upload.addEventListener("progress", function (evt) {
                    if (evt.lengthComputable) {
                        var percentComplete = evt.loaded / evt.total;
                        percentComplete = parseInt(percentComplete * 100);
                        $('.determinate').css('width', percentComplete + '%');
                    }
                }, false);
                return xhr;
            },
            error: function(error){
			      console.log(error);
			},
            success: function (data) { 

                if(data.statu==true){

                	$('#newProduct').modal('close'); //On ferme la fenêtre
                	notification("Nouveau produit enregistré");
                	$('.initiate').val(''); //On efface les entrées

                	$('#Productcategorie').val('No Category')
                	$('#Productcategorie').formSelect() ;

                	$('#ImageProduct').val(null); 
                	
                	$('.warning_message').hide();
                	$('.warning_message span').text('');

                }else{

                	$('.warning_message span').html(data.message);
                	$('.warning_message').show();
                }

                $('.addNewImage').fadeOut();//We add progressBar
            }
        });
    }


 
    $('#ImageProduct').change(function () {
            if (this.files.length > 0) {

                $.each(this.files, function (index, value) {
                    console.log( Math.round((value.size / 1024)))
                   
                })
            }
    });



    function notification(message) {
    	
    	M.toast({html: message})
    }


    ///Manage the pulse effect
    $('.big_lister .btn-floating').click(function () {
    	
    	$('.big_lister .btn-floating').removeClass('pulse');

    	$(this).addClass('pulse');

    	$('.displayer').html('') //We wipe the displayer
    })


    $('.listProduit').click(function () {
    	
    	$('.lister').fadeIn() //We show the bottons of diffrent menu list

    	//On click déjà sur la liste des accessoires
    	$('.lister .btn').removeClass('pulse')
    	$('.accessoires').addClass('pulse')

    	get_products('lister','Bijoux') 

    	$('.displayer').html('') //We wipe the displayer

    })


    $('.lister .btn').click(function () {
    	
    	$('.lister .btn').removeClass('pulse')
    	$(this).addClass('pulse')

    	$('.displayer').html('') //We wipe the displayer
    	
    })


    $('.livraisons,.commandes,.ajouter').click(function () {
    	
    	$('.lister').fadeOut();
    })


    function get_products(type,clause) {

    	$.ajax({
	       	url :$('.hider').attr('site_url')+$('.hider').attr('get_it') , // La ressource ciblée
	       	type : 'POST', // Le type de la requête HTTP.
	       	data : {'clause':clause,'type':type},
	       	error: function (error) {
	       	 	console.log(error)
	       	},
	       	dataType : 'json', // Le type de données à recevoir, ici, du HTML.
	       	success:function (data) {

	       		if(data==null){
	       			console.log('no data')
	       		}else{
	       			for (var i = 0; i < data.length; i++) {
	       				
	       				CardBuilder(data[i])
	       			}
	       		}
	       	}
	    });
    }


    $('.disponibility input').on('change', function() {

    	alert('Vous avez changé le statu de disponibilité du produit.')
   		 
   		// alert($('input[name=group1]:checked').val()); 
	});

    function CardBuilder(data) { //Build all cards

    	//We prepare the promo price displayer
    	if(data.ProductPromoPrice>0){

    		ProductPromoPrice = '<s><span class="promo">'+data.ProductPromoPrice+' FCFA</span></s>' ;
    	}else{
    		ProductPromoPrice = '';
    	}


    	//We prepare the radio bouton
    	if(data.disponible=='oui'){

    		var oui = '<input name="group1" type="radio" value="oui" checked />';
    		var non = '<input name="group1" type="radio" value="non" />';
    	}else{
    		var oui = '<input name="group1" type="radio" value="oui"  />';
    		var non = '<input name="group1" type="radio" value="non" checked />';
    	}
    	
    	var html = '<div class="col s12 m4 l3" id="'+data.ProductId+'">';
          	html +='<div class="card"><div class="card-image waves-effect waves-block waves-light">';
          	html +='<img class="activator" src="'+$('.hider').attr('base_url')+'/assets/photo/'+data.ProductPicture+'">';
          	html +='</div><div class="card-content">';
          	html +='<span class="card-title activator grey-text text-darken-4">'+data.ProductName+'<i class="material-icons right">more_vert</i></span>';
          	html +='<p class="red-text text-darken-2"><span class="price">'+data.ProductPrice+' FCFA</span> '+ProductPromoPrice+' </p>';
          	html +='<p> <span class="blue-text text-darken-2 categorie_text">'+data.ProductCategory+'</span> <br> Commandes:'+data.ProductCommande+' <br>';
          	html +='Ventes:'+data.ProductVente+'</p><p><form action="#">';
          	html +='<p class="disponibility"><label>'+oui+'<span>Disponible</span>';
          	html +='</label><label>'+non+'<span>Indisponible</span></label></p></form></p></div>';
          	html +='<div class="card-reveal"><span class="card-title grey-text text-darken-4">'+data.ProductName+'<i class="material-icons right">close</i></span>';
          	html +='<div class="row"><form class="col s12"><div class="row"><div class="input-field col s12">';
          	html +='<input placeholder="'+data.ProductName+'" id="name_'+data.ProductId+'" type="text" class="validate name">';
          	html +='<label for="name_'+data.ProductId+'">Nom du produit</label></div><div class="input-field col s12">';
          	html +='<input placeholder="'+data.ProductPrice+'" id="prix_'+data.ProductId+'" type="text" class="validate prix"><label for="prix_'+data.ProductId+'">Prix</label>';
          	html +='</div><div class="input-field col s12"><input placeholder="'+data.ProductPromoPrice+'" id="promotion_'+data.ProductId+'" type="text" class="validate promotion">';
          	html +='<label for="promotion_'+data.ProductId+'">Prix Promotionnel</label></div><center><div class="row"><div class="col s4">';
          	html +='<a class="btn-floating btn-large waves-effect waves-light blue"><i class="material-icons left">photo</i></a></div><div class="col s4">';
          	html +='<a class="btn-floating btn-large waves-effect waves-light blue"><i class="material-icons">save</i></a></div><div class="col s4">';
          	html +='<a class="btn-floating btn-large waves-effect waves-light red"><i class="material-icons">delete_forever</i></a></div></div>';
          	html +='</center></div></form></div></div></div></div>';

          	$('.displayer').append(html);
    }

  });

 