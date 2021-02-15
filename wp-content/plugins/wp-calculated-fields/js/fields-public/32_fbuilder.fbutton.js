	$.fbuilder.controls[ 'fButton' ]=function(){};
	$.extend(
		$.fbuilder.controls[ 'fButton' ].prototype,
		$.fbuilder.controls[ 'ffields' ].prototype,
		{
			ftype:"fButton",
            sType:"button",
            sValue:"button",
			sLoading:false,
            sOnclick:"",
			userhelp:"A description of the section goes here.",
			show:function()
				{
                    var esc  = function( v ){ v = v.replace( /&lt/g, '&amp;').replace(/"/g, "&quot;").replace( /\n+/g, ' ' ); return v;},
                        type = this.sType,
                        clss = '';

                    if( this.sType == 'calculate' )
                    {
                        type = 'button';
                        clss = 'calculate-button';
                    }
					else if( this.sType == 'reset' )
					{
						clss = 'reset-button';
					}

                    return '<div class="fields '+this.csslayout+' '+this.name+' cff-button-field" id="field'+this.form_identifier+'-'+this.index+'"><input id="'+this.name+'" type="'+type+'" value="'+esc( this.sValue )+'" class="field '+clss+'" /><span class="uh">'+this.userhelp+'</span><div class="clearer"></div></div>';
				},
            after_show:function()
                {
					var me = this;
					$( '#'+this.name ).click(
                        function()
                            {
                                var e = $( this );
                                if( e.hasClass( 'calculate-button' ) )
                                {
                                    var items = $.fbuilder[ 'forms' ][ me.form_identifier ].getItems();
									if(me.sLoading) $('<div class="cff-processing-form"></div>').appendTo(e.closest('#fbuilder'));
									$(document).on('equationsQueueEmpty', function(evt, id){
										if(id == me.form_identifier)
										{
											if(me.sLoading) e.closest('#fbuilder').find('.cff-processing-form').remove();
											$(document).off('equationsQueueEmpty');
											for(var i = 0, h = items.length; i < h; i++ )
											{
												if(items[i].ftype == 'fsummary')
												{
													items[i].update();
												}
											}
										}
									});

                                    $.fbuilder[ 'calculator' ].defaultCalc( '#'+e.closest( 'form' ).attr( 'id' ), false );
                                }
								if( e.hasClass( 'reset-button' ) )
								{
									setTimeout(
										function()
										{
											var identifier = e.closest( 'form' ).attr( 'id' ).replace( /cp_calculatedfieldsf_pform/, '' );
											e.closest('form').find(':data(manually)').removeData('manually');
											$.fbuilder[ 'showHideDep' ]( { 'formIdentifier' : identifier } );

											var page = parseInt( e.closest( '.pbreak' ).attr( 'page' ) );
											if( page )
											{
												$.fbuilder.forms[identifier]['currentPage'] = 0;
												$("#fieldlist"+identifier+" .pbreak").css("display","none");
												$("#fieldlist"+identifier+" .pbreak").find(".field").addClass("ignorepb");
												$("#fieldlist"+identifier+" .pb0").css("display","block");
												if ($("#fieldlist"+identifier+" .pb0").find(".field").length>0)
												{
													$("#fieldlist"+identifier+" .pb0").find(".field").removeClass("ignorepb");
													try
													{
														$("#fieldlist"+identifier+" .pb0").find(".field")[0].focus();
													}
													catch(e){}
												}
											}
										},
										50
									);
								}
								eval(me.sOnclick);
                            }
                    );
                }
		}
	);