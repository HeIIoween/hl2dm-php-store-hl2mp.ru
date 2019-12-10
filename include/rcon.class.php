<?php
class Rcon {
	
	private $socket;
	private $auth = false;
	private $RequestId = 0;
	
	const SERVERDATA_AUTH = 3;
	const SERVERDATA_AUTH_RESPONSE = 2;
	const SERVERDATA_EXECCOMMAND = 2;
	const SERVERDATA_RESPONSE_VALUE = 0;
	
	public function __construct( $host, $port, $password, $timeout = 30 )
	{
		$this->socket = @fsockopen('tcp://'.$host, $port, $errno, $errstr, $timeout);
		
		if( !$this->socket )
			die('Erreur de socket: '. $errstr);
		
		$this->write(self::SERVERDATA_AUTH, $password);
		$buffer = $this->read();

		$request = $this->GetLong($buffer);
		$code = $this->GetLong($buffer);

		if( $request == -1 || $code != self::SERVERDATA_AUTH_RESPONSE )
			die('RCON authorization failed.');

		$this->auth = true;

		return true;
	}
	
	public function command( $command )
	{
		if( !$this->auth || !$this->socket )
			die('Class is not ready. Make sure you are connected and authenticated.');
		
		$this->write(self::SERVERDATA_EXECCOMMAND, $command);
		$this->write();
		return $this->read();
	}
	
	private function write( $type = null, $string = null )
	{
		if( $string != null )
			$packet = pack('VV', ++$this->RequestId, $type) . $string . "\x00\x00\x00";
		else
			$packet = pack('VV', ++$this->RequestId, 0);
		
		$packet = pack('V', strlen($packet)) . $packet;
		
		return fwrite($this->socket, $packet);
	}
	
	public function read()
	{
		if( !$this->auth ) {
			$size = $this->GetLong(fread($this->socket, 4));
			$junk = fread($this->socket, $size);
			$size = $this->GetLong(fread($this->socket, 4));
			return fread($this->socket, $size);
		}
		
		while( $size != 10 ) {
			$size = $this->GetLong(fread($this->socket, 4));
			$packet = fread($this->socket, $size);
			$request = $this->GetLong($packet);
			$code = $this->GetLong($packet);
			if( $code != self::SERVERDATA_RESPONSE_VALUE )
				return false;

			$buffer .= substr( $packet, 0, strlen( $packet ) - 2 );
		}
		fclose( $this->socket );
		return $buffer;
	}
	
	private function getLong( &$string )
	{
		$long = unpack('l', substr($string, 0, 4));
		$string = substr($string, 4);
		return $long[1];
	}
}