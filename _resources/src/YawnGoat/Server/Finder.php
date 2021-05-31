<?php
namespace YawnGoat\Server;
/*==============================
use YawnGoat\Server\Finder as ServerInfo;
Desc:
*/
class Finder{
	/*==============================
	Desc:鯖側OS検出
	*/
	public static function osIsWin(){ return (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN');}
	public static function osIsNix(){ return (Converter::osIsWin() == false);}
}
?>