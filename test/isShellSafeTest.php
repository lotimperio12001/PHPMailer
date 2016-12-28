<?php
/**
 * PHP version 5.0.0
 *
 * @package PHPMailer
 * @author Elan Ruusamäe <glen@delfi.ee>
 * @copyright 2004 - 2009 Andy Prevost
 * @copyright 2016 Marcus Bointon
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License
 */

require_once '../PHPMailerAutoload.php';

class isShellSafeTest extends PHPUnit_Framework_TestCase
{
    /**
     * Holds a phpmailer instance.
     * @var PHPMailer|PHPMailerShellSafeWrapper
     */
    private $Mail;

    /**
     * Run before each test is started.
     */
    public function setUp()
    {
        $this->Mail = new PHPMailerShellSafeWrapper;
    }

    /**
     * @dataProvider goodShellSafeData
     */
    public function testIsShellSafe($s)
    {
        $res = $this->Mail->isShellSafeAccessible($s);
        $this->assertTrue($res);
    }

    /**
     * @dataProvider badShellSafeData
     */
    public function testNotShellSafe($s)
    {
        $res = $this->Mail->isShellSafeAccessible($s);
        $this->assertFalse($res);
    }

	/**
	 * Provide data set which should return true for isShellSafe()
	 */
    public function goodShellSafeData()
    {
        return array(
            array(''),
            array('a'),
            array('@._-'),
            array(static::charRange('a', 'z')),
            array(static::charRange('A', 'Z')),
            array(static::charRange('0', '9')),
        );
    }

	/**
	 * Provide data set which should return false for isShellSafe()
	 */
    public function badShellSafeData()
    {
        return array(
            array(false),
            array(null),
            array("'test\\\"test'@test.test"),
            array('\\'),
            array('/'),
        );
    }

	private function charRange($a, $b) {
		return self::byteRange(ord($a), ord($b));
	}

	private function byteRange($a, $b) {
		$s = '';
		foreach (range($a, $b) as $c) {
			$s .= chr($c);
		}
		return $s;
	}
}

/**
 * @internal
 */
class PHPMailerShellSafeWrapper extends PHPMailer {
    public function isShellSafeAccessible($s)
    {
        return $this->isShellSafe($s);
    }
}
