	$.fbuilder.controls[ 'fdropdown' ]=function(){};
	$.extend(
		$.fbuilder.controls[ 'fdropdown' ].prototype,
		$.fbuilder.controls[ 'ffields' ].prototype,
		{
			title:"Select a Choice",
			ftype:"fdropdown",
			size:"medium",
			required:false,
			toSubmit:"text",
			merge:0,
			choiceSelected:"",
			multiple:false,
			vChoices:1,
			showDep:false,
			show:function()
				{
					this.choicesVal = ((typeof(this.choicesVal) != "undefined" && this.choicesVal !== null)?this.choicesVal:this.choices)

					var c	 = this.choices,
						cv	 = this.choicesVal,
						og   = (typeof this.optgroup == 'undefined') ? new Array() : this.optgroup,
						op_o = false,
						l 	 = c.length,
						classDep = '',
						str  = '';

					if ( typeof this.choicesDep == "undefined" || this.choicesDep == null )
						this.choicesDep = new Array();

					for (var i=0;i<l;i++)
					{
						if( typeof this.choicesDep[i] != 'undefined' && (typeof og[i] == 'undefined' || !og[i]))
							this.choicesDep[i] = $.grep(this.choicesDep[i],function(n){ return n != ""; });
						else
							this.choicesDep[i] = [];

						if( this.choicesDep[i].length && (typeof og[i] == 'undefined' || !og[i]) )
							classDep = 'depItem';
					}

					for (var i=0;i<l;i++)
					{
						if(og[i])
						{
							if(op_o) str += '</optgroup>';
							str += '<optgroup label="'+$.fbuilder.htmlEncode(c[i])+'">';
							op_o = true;
						}
						else
						{
							str += '<option '+((this.choiceSelected == c[i]+' - '+cv[i])?"selected":"")+' '+( ( classDep != '' ) ? 'class="'+classDep+'"' : '' )+' value="'+$.fbuilder.htmlEncode(cv[i])+'" vt="'+$.fbuilder.htmlEncode((this.toSubmit=='text') ? c[i] : cv[i])+'" data-i="'+i+'">'+c[i]+'</option>';
						}
					}
					if(op_o) str += '</optgroup>';
					return '<div class="fields '+this.csslayout+' '+this.name+' cff-dropdown-field" id="field'+this.form_identifier+'-'+this.index+'"><label for="'+this.name+'">'+this.title+''+((this.required)?"<span class='r'>*</span>":"")+'</label>'+
					'<div class="dfield"><select aria-label="'+$.fbuilder.htmlEncode(this.title)+'" id="'+this.name+'" name="'+this.name+((this.multiple)? '[]':'')+'" class="field '+( ( classDep != '' ) ? ' depItemSel ' : '' )+this.size+((this.required)?' required':'')+'" '+((this.multiple == true)?' multiple="multiple" size="'+((this.vChoices) ? this.vChoices : 1)+'"':'')+'>'+str+'</select><span class="uh">'+this.userhelp+'</span></div><div class="clearer"></div><div class="clearer"></div></div>';
				},
			showHideDep:function( toShow, toHide, hiddenByContainer )
				{
					var me = this,
						item = $( '#'+me.name+'.depItemSel' ),
						form_identifier = me.form_identifier,
						isHidden = ( typeof toHide[ me.name ] != 'undefined' || typeof hiddenByContainer[ me.name ] != 'undefined' ),
						result = [];

					try
					{
						if( item.length )
						{
							var selected = [];
							$(item).find(':selected').each(function(){selected.push($(this).data('i'));});

							for( var i = 0, h = me.choices.length; i < h; i++ )
							{
								if( typeof me.choicesDep[i] != 'undefined' && me.choicesDep[ i ].length )
								{
									for( var j = 0, k = me.choicesDep[ i ].length; j < k; j++)
									{
										if(!/fieldname/i.test(me.choicesDep[i][j])) continue;
										var dep = me.choicesDep[i][j]+form_identifier;
										if( isHidden || $.inArray(i,selected) == -1)
										{
											if( typeof toShow[ dep ] != 'undefined' )
											{
												delete toShow[ dep ][ 'ref' ][ me.name+'_'+i ];
												if( $.isEmptyObject(toShow[ dep ][ 'ref' ]) )
												delete toShow[ dep ];
											}

											if( typeof toShow[ dep ] == 'undefined' )
											{
												$( '[id*="'+dep+'"],.'+dep ).closest( '.fields' ).hide();
												$( '[id*="'+dep+'"]:not(.ignore)' ).addClass( 'ignore' );
												toHide[ dep ] = {};
											}
										}
										else
										{
											delete toHide[ dep ];
											if( typeof toShow[ dep ] == 'undefined' )
											toShow[ dep ] = { 'ref': {}};
											toShow[ dep ][ 'ref' ][ me.name+'_'+i ]  = 1;
											if(!(dep in hiddenByContainer))
											{
												$( '[id*="'+dep+'"],.'+dep ).closest( '.fields' ).show();
												$( '[id*="'+dep+'"].ignore' ).removeClass( 'ignore' );
											}
										}
										if($.inArray(dep,result) == -1) result.push( dep );
									}
								}
							}
						}
					}
					catch( e ){}
					return result;
				},
			val:function(raw)
				{
					raw = raw || false;
					var e = $( '[id="' + this.name + '"]:not(.ignore) option:selected' ),
						v,
						m = this.multiple,
						g = this.merge && !raw;

					if(m && !g)	v = [];
					if(e.length)
					{
						e.each(function(){
							var t = $.fbuilder.parseValStr((raw == 'vt') ? this.getAttribute('vt') : this.value, raw);
							if(!$.isNumeric(t)) t = t.replace(/^"/,'').replace(/"$/,'');
							if(!m || g) v = (v)?v+t:t;
							else v.push(t);
						});
					}
					return (typeof v == 'object' && typeof v['length'] !== 'undefined') ? v : ((v) ? (($.isNumeric(v)) ? v : '"'+v+'"') : 0);
				},
			setVal:function( v )
				{
					if( !$.isArray( v ) ) v = [v];
					var t, n = this.name;
					for(var i in v)
					{
						t = (new String(v[i])).replace(/(['"])/g, "\\$1");
						$( '[id="'+n+'"] OPTION[vt="'+t+'"],[id="'+n+'"] OPTION[value="'+t+'"]' ).prop( 'selected', true );

					}
					$( '[id="'+n+'"]' ).change();
				},
			setChoices:function(choices)
				{
					if($.isPlainObject(choices))
					{
						var bk = this.val(true);
						if('texts' in choices && $.isArray(choices.texts)) this.choices = choices.texts;
						if('values' in choices && $.isArray(choices.values)) this.choicesVal = choices.values;
						if('dependencies' in choices && $.isArray(choices.dependencies)) this.choicesDep = choices.dependencies;
						if('optgroup' in choices && $.isArray(choices.optgroup)) this.optgroup = choices.optgroup;
						var html = this.show(),
							field = $('.'+this.name).replaceWith(html);
						this.setVal(bk);
					}
				}
		}
	);