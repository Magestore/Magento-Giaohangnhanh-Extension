GiaoHangNhanh = Class.create();
GiaoHangNhanh.prototype = {
	initialize: function(giaohangnhanh,city,cityInput,pickhubContainer,serviceContainer,giaohangnhanhDetail,content,changePickHubUrl,changeServiceUrl) {
		this.giaohangnhanh = giaohangnhanh;
		this.city = city;
		this.cityInput = cityInput;
		this.pickhub_container = pickhubContainer;
		this.service_container = serviceContainer;
		this.giaohangnhanh_detail = giaohangnhanhDetail;
		this.content = content;
		this.changePickHubUrl = this.addUrl(changePickHubUrl);
		this.changeServiceUrl = this.addUrl(changeServiceUrl);
		this.check = false;
		setInterval(this.updateContent.bind(this), 100);
		setInterval(this.checkCityInput.bind(this), 100);
		var coShippingMethodFormGHN = new VarienForm('co-shipping-method-form');
            coShippingMethodFormGHN.submit = function () {
				if (clForm.validator.validate() == false) {
				return false;
				}
                return VarienForm.prototype.submit.bind(coShippingMethodFormGHN)();
        }
	},
	
	checkCityInput: function(){
		if(typeof $(this.cityInput) != 'undefined' && $(this.cityInput) && !this.check){
			this.check = true;
			Event.observe($(this.cityInput), 'change',  this.changePickHub.bind(this));		
		}
	},
	
	addUrl: function(url){
		if (window.location.href.match('https://') && !url.match('https://')) {
            url = url.replace('http://', 'https://');
        }
        if (!window.location.href.match('https://') && url.match('https://')) {
            url = url.replace('https://', 'http://');
        }
        return url;
	},
			
 	updateContent: function() {
		if(typeof $(this.giaohangnhanh) != 'undefined' && $(this.giaohangnhanh) && (typeof $(this.giaohangnhanh_detail) == 'undefined' || !$(this.giaohangnhanh_detail))){
			$(this.giaohangnhanh).up('ul').insert({
				after: this.content
			});
			setInterval(this.giaohangnhanhChecked.bind(this), 100);
			if(typeof $(this.pickhub_container).down('#pickhub') != 'undefined' && $(this.pickhub_container).down('#pickhub')){
				Event.observe( $(this.pickhub_container).down('#pickhub'), 'change',  this.changePickHub.bind(this)); 
				if($(this.pickhub_container).down('#pickhub').value);
					this.changePickHub();
			}
			
		}
	},	
	
	giaohangnhanhChecked: function() {
		if(typeof $(this.giaohangnhanh_detail) == 'undefined' || !$(this.giaohangnhanh_detail))
			return this;
		if($(this.giaohangnhanh).checked){
			$(this.giaohangnhanh_detail).show();
		}else{
			$(this.giaohangnhanh_detail).hide();
		}
	},
	
	changePickHub: function(el) {
		if(typeof $(this.pickhub_container).down('#pickhub') == 'undefined' || typeof $(this.pickhub_container) == 'undefined' || !$(this.pickhub_container))
			return this;
		var parrent = this;
		if(typeof $(this.cityInput) == 'undefined' && $(this.cityInput).value)
			this.city = $(this.cityInput).value;
		this.showAjaxPopup();
		new Ajax.Request(this.changePickHubUrl, {
			method: 'post',
			parameters: {
				city: this.city,
				pickhub:  $(this.pickhub_container).down('#pickhub').value				
			},
			beforeRequest: function () {
				parrent.showAjaxPopup();
			},
			onComplete: function (xhr) {
				if (xhr.responseText.isJSON()) {
					var response = xhr.responseText.evalJSON();
					if(response){
						parrent.updateServices(response);
					}
				}
				parrent.closeAjaxPopup();
			}
		});
	},
	
 	updateServices: function(services) {
		$(this.service_container).update(services);
		if(typeof $(this.service_container).down('#service') != 'undefined' && $(this.service_container).down('#service')){
			Event.observe( $(this.service_container).down('#service'), 'change',  this.changeService.bind(this)); 
			if($(this.service_container).down('#service').value)
				this.changeService();
		}
	},
	
	changeService: function(el) {
		var parrent = this;
		this.showAjaxPopup();
		new Ajax.Request(this.changeServiceUrl, {
			method: 'post',
			parameters: {
				city: this.city,
				pickhub:  $(this.pickhub_container).down('#pickhub').value,
				service:  $(this.service_container).down('#service').value				
			},
			beforeRequest: function () {
				parrent.showAjaxPopup();
			},
			onComplete: function (xhr) {
				if (xhr.responseText.isJSON()) {
					var response = xhr.responseText.evalJSON();
					if(response){
						parrent.updateShippingFee(response);
					}
				}
				parrent.closeAjaxPopup();
			}
		});
	},
	
	updateShippingFee: function(price) {
		if ($(this.giaohangnhanh) && $(this.giaohangnhanh).up('li') && $(this.giaohangnhanh).up('li').down('.price')) {
			$(this.giaohangnhanh).up('li').down('.price').update(price);
			this.reloadTotal();
		}
	}, 
	
	showAjaxPopup: function(){
		$('shipping-popup-overlay').style.display = 'block';
	},
	closeAjaxPopup: function(){
		$('shipping-popup-overlay').style.display = 'none';
	},
	reloadTotal: function() {
    if ($$('body.onestepcheckout-index-index')[0] && typeof save_address_information == 'function') {
        save_address_information(save_address_url, 0, 0, 1);
    }
}
}
