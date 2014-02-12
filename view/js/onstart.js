/**
* Das ganze wird erst ausgeführt, wenn der ganze DOM geladen wurde
*/
$(document).ready(function() {
						/**
						 * den Tabellen sagen sie sollen ein Datatable sein (Sortieren und Suche von Tables möglich)
						 */
                          $('#admintable').dataTable();
                           $('#videotable').dataTable();
                           /**
                            * Verschiedene Formulare validieren lassen
                            */
                           $("#registerForm").validate();
           					$("#userhomepw").validate();
           					$("#userhomeusername").validate();
                           $("#videosubmit").validate({      
                                  messages: {
                                  dd1: {
                                        required: "Please do not select 'Choose Genre'",
                                  }
                                  }
                           });
                           
                           
                           /**
                            * Sobald der Button geklickt wird bzw. das Item so wird ein toggle Effekt ausgeführt (div anzeigen/ausblenden)
                            */
                           $( "#showform" ).click(function() {
                                    $( "#submitform" ).toggle( "blind", 1000 );
                           });    

                           $( "#addusername" ).click(function() {
                                    $( "#usernamechange" ).toggle( "blind", 250 );
                           });    


                           $( "#addpassword" ).click(function() {
                                    $( ".passwordchange" ).toggle( "blind", 250 );
                           });    
                           

                           /**
                            * verschiedenen Divs sagen sie sind nun Ratys was für die Bewertung ist, dazu noch verschiedene Optionen
                            * readonly das man nciht bewerten kann, path wo die images sind, score für wieviele Sterne markiert sind, hints für den hover effekt
                            */
                           $(".viewrating").raty({ 
                                  readOnly: true,
                                  path: '/view/images/', 
                                  score: function() {
                                        return $(this).attr('data-score');
                                  },
                                  hints: ['worse', 'bad', 'normal', 'good', 'perfect'],
                                  width: 250
                           });
                           /**
                            *hier dazu noch ein Ajax-Request um die Sachen von jQuery zu php zu senden dass die Bewertung in die
                            *Datenbank gespeichert werden können
                            */
                           $(".basicrating").raty({
                                  path: '/view/images/',
                                  score: function() {
                                        return $(this).attr('data-score');
                                  },
                                  click: function(score, evt) {
                                      $.ajax({
                                            type: 'POST',
                                            url: '/Ajax/saverating',
                                            data: {'score':score, 'currenturl':window.location.pathname}, 
                                            success: function(data){ $('#trufa').html(data); }
                                      });
                               },
                                  hints: ['worse', 'bad', 'normal', 'good', 'perfect'],
                                  width: 250
                           });
                           /**
                           * Das wird ausgeführt wenn man auf den More button klickt
                           */
                           $("#morebutton").click(function(){
                                  $.ajax({
                                        //Es wird eine admin request über Post gesendet
                                        type: "POST",
                                        url: "/Ajax/morevideos",
                                        data: {videos:$("#hiddenval").val(),argument:$("#argument").val()},
                                        success: function(data){
                                               //Wenn das Ajax fertig ist, werden die daten zum allvideos hinzugefügt
                                               $("#allvideos").append(data);
                                               setTimeout(function(){
                                                      //nach einem timout wird die bewertung der neuen viedeos geladen
                                                      $((".viewrating"+$("#hiddenval").val())).raty({ 
                                                            readOnly: true,
                                                            path: '/view/images/', 
                                                            score: function() {
                                                                   return $(this).attr('data-score');
                                                            },
                                                            hints: ['worse', 'bad', 'normal', 'good', 'perfect'],
                                                            width: 250
                                                      });
                                                      //Setzt den wert vom #hiddenval um 5 höher
                                                      $("#hiddenval").val(parseInt($("#hiddenval").val())+5);
                                               },25);
                                               
                                        }
                                  });
                           });

                    });
