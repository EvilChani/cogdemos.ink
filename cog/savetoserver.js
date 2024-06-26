Scene.prototype.resetPage = function resetPage()
	{
		var scene = window.stats.scene;
		ink_password = createPassword( scene.stats , scene.temps , scene.lineNum , scene.indent );
		//console.log( ink_password );
		var self = this;
		clearScreen(function() {
			self.save("");
			self.prevLine = "empty";
			self.screenEmpty = true;
			self.execute();
		});
	}

function inkLogin()
	{
		if( typeof ink_password == 'undefined' )
			{ alertify.log( "Could not load temporary save." );return; }
		setCookie( "temppass", ink_password, 1 );
	}

function inkLoginLoad()
	{
		ink_password = getCookie( "temppass" );
		setCookie( "temppass", "", 1 );
		if( typeof ink_password == 'undefined' )
			{ alertify.log( "Could not load temporary save." );return; }
		restore_localStorage( ink_password );
	}

function inkSave( slotID = 1 )
	{
		if( typeof ink_password == 'undefined' )
			{ alertify.log( "Cannot save on an immediate load screen." );return; }
		sendXMLDoc( ink_password, slotID );
	}

function inkLoad( slotID = 1 )
	{
		var this_js_script = $('script[src*=savetoserver]');
		var gameID = this_js_script.attr('data-game');
		if (typeof gameID === "undefined" ) { var gameID = 0; }
		var request = new XMLHttpRequest();
		request.open( "GET", "https://cogdemos.ink/load/" + gameID + "/" + slotID, true );
		request.onload = function()
			{
				if ( request.status >= 200 && request.status < 400 )
					{
						var resp = request.responseText;
						restore_localStorage( resp );
						alertify.log( "<b>Load successful</b>" );
					}
				else
					{
						console.log( "servererror" );
						alertify.log( "<b>Load FAILED</b>" );
					}
			};
		request.onerror = function()
			{ console.log( "error" ); };
		request.send();
	}

function loadJSON( path, success, error )
	{
		var xhr = new XMLHttpRequest();
		xhr.onreadystatechange = function()
			{
					if( xhr.readyState === XMLHttpRequest.DONE )
						{
							if ( xhr.status === 200 )
								{
									if ( success )
										{ success( xhr.responseText ); }
								}
							else
								{
									if ( error )
										{ error( xhr ); }
								}
						}
			};
		xhr.open( "GET", path, true );
		xhr.send();
	}

function sendXMLDoc( ink_password, slotID )
	{
		var xmlhttp;
		try
			{
				xmlhttp = new XMLHttpRequest();
			}
		catch ( e )
			{
				try
					{ xmlhttp = new ActiveXObject( "Msxml2.XMLHTTP" ); }
				catch ( e )
					{
					try
						{ xmlhttp = new ActiveXObject( "Microsoft.XMLHTTP" ); }
					catch ( e )
						{
							console.log( "Your browser broke!" );
							return false;
						}
					}
			}
		var this_js_script = $('script[src*=savetoserver]');
		var gameID = this_js_script.attr('data-game');
		if (typeof gameID === "undefined" )
			{ alertify.log( "Could not identify game to store save." );return; }
		url = "https://cogdemos.ink/save/" + gameID + "/" + slotID;
		console.log(url);
		xmlhttp.open( "POST", url, true );
		xmlhttp.onreadystatechange = display_data;
		xmlhttp.setRequestHeader( "Content-Type", "application/x-www-form-urlencoded" );  
		xmlhttp.send( "status=" + ink_password );
		function display_data()
			{
				if( xmlhttp.readyState == 1 )
					{ console.log( "OPENED" ); }
				if( xmlhttp.readyState == 2 )
					{ console.log( "Headers Received" ); }
				if( xmlhttp.readyState == 3 )
					{ console.log( "Loading response entity body" ); }
				if( xmlhttp.readyState == 4 )
					{
						if (xmlhttp.status == 200)
							{
								console.log( "Data transfer completed" );
								alertify.log( "<b>Save successful</b>" );
							}
						else
							{
								console.log( "Data transfer failed" );
								alertify.log( "<b>Save FAILED</b>" );
							}
					}
			}
	}

function createPassword( stats, temps, lineNum, indent )
	{
		var scene = stats.scene;
		delete stats.scene;
		if( scene )
			{ stats.sceneName = scene.name; }
		var version = "UNKNOWN";
		if ( typeof( window ) != "undefined" && window && window.version ) version = window.version;
		var value = toJson(
				{ version:version, stats:stats, temps:temps, lineNum: lineNum, indent: indent }
			);
		stats.scene = scene;
		return value;
	}

function restore_localStorage( password )
	{
		try
			{ var state = jsonParse( password ); }
		catch( e )
			{
				console.log( "Sorry, that password is invalid." );
				console.log( password );
				return;
			}
		var sceneName = null;
		if ( state.stats && state.stats.sceneName )
			{ sceneName = ( "" + state.stats.sceneName ).toLowerCase(); }
		saveCookie( function()
			{
				clearScreen( function()
						{ restoreGame( state, null, true ); }
					)
			}, "", state.stats, state.temps, state.lineNum, state.indent, this.debugMode, this.nav );
		console.log( "Load successful!" );
	}

function setCookie( name, value, days )
	{
		var expires = "";
		if ( days )
			{
				var date = new Date();
				date.setTime( date.getTime() + ( days * 24 * 60 * 60 * 1000 ) );
				expires = "; expires=" + date.toUTCString();
			}
		document.cookie = name + "=" + ( value || "" ) + expires + "; path=/";
	}

function getCookie( name )
	{
		var nameEQ = name + "=";
		var ca = document.cookie.split( ';' );
		for ( var i = 0; i < ca.length; i++ )
			{
				var c = ca[i];
				while ( c.charAt(0) == ' ' ) c = c.substring( 1, c.length );
				if( c.indexOf(nameEQ) == 0 ) return c.substring( nameEQ.length, c.length );
			}
		return null;
		}
