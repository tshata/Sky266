	$.fbuilder.controls[ 'femail' ] = function(){};
	$.extend(
		$.fbuilder.controls[ 'femail' ].prototype,
		$.fbuilder.controls[ 'ffields' ].prototype,
		{
			title:"Email",
			ftype:"femail",
			predefined:"",
			predefinedClick:false,
			required:false,
			readonly:false,
			size:"medium",
			equalTo:"",
			regExp:"",
			regExpMssg:"",
			show:function()
				{
					this.predefined = this._getAttr('predefined');
					return '<div class="fields '+this.csslayout+' '+this.name+' cff-email-field" id="field'+this.form_identifier+'-'+this.index+'"><label for="'+this.name+'">'+this.title+''+((this.required)?"<span class='r'>*</span>":"")+'</label><div class="dfield"><input aria-label="'+$.fbuilder.htmlEncode(this.title)+'" id="'+this.name+'" name="'+this.name+'" '+((this.equalTo!="")?"equalTo=\"#"+$.fbuilder.htmlEncode(this.equalTo+this.form_identifier)+"\"":"" )+' class="field email '+this.size+((this.required)?" required":"")+'" type="email" value="'+$.fbuilder.htmlEncode(this.predefined)+'" '+((this.readonly)?'readonly':'')+' /><span class="uh">'+this.userhelp+'</span></div><div class="clearer"></div></div>';
				},
			after_show:function()
				{
					if( this.regExp != "" && typeof $[ 'validator' ] != 'undefined' )
					{
						var parts 	= this.regExp.match(/(\/)(.*)(\/)([gimy]{0,4})$/i);
						this.regExp = ( parts === null ) ? new RegExp(this.regExp) : new RegExp(parts[2],parts[4].toLowerCase());

						if(!('pattern' in $.validator.methods))
							$.validator.addMethod( 'pattern', function( value, element, param )
								{
									try{
										return this.optional(element) || param.test( value );
									}
									catch(err){return true;}
								}
							);
						$('#'+this.name).rules('add',{'pattern':this.regExp, messages:{'pattern':this.regExpMssg}});
					}
				},
			val:function(raw)
				{
					raw = raw || false;
					var e = $( '[id="' + this.name + '"]:not(.ignore)' );
					if( e.length ) return $.fbuilder.parseValStr( e.val() , raw);
					return 0;
				}
		}
	);