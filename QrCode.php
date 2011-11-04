<?php
/**
 * QR Code Model
 *
 * This class is a model of a qr code that implements the Google Chart API for
 * code generation.
 */

/**
 * QrCode
 *
 * @author Eric Famiglietti <eric.famiglietti@gmail.com>
 */
class QrCode
{

    /**
     * $_size - the size of the qr code
     *
     * @var int
     */
    private $_size = null;

    /**
     * $_data - the data to be encoded
     *
     * @var string
     */
    private $_data = null;

    /**
     * $_outputEncoding
     *
     * @var string
     */
    private $_outputEncoding = null;

    /**
     * $_errorCorrectionLevel
     *
     * @var string
     */
    private $_errorCorrectionLevel = null;

    /**
     * $_margin - the margin of the qr code
     *
     * @var int
     */
    private $_margin = null;

    /**
     * MINIMUM_SIZE - the minimum size in pixels of a qr code
     *
     * @var int
     */
    const MINIMUM_SIZE = 100;

    /**
     * MAXIMUM_SIZE - the maximum sizr in pixels of a qr coee
     *
     * @var int
     */
    const MAXIMUM_SIZE = 545;

    /**
     * $_validOutputEncodings - the valid output encodings that are accepted
     *
     * @var array
     */
    private static $_validOutputEncodings = array(
        'UTF-8',
        'Shift_JIS',
        'ISO-8859-1',
    );

    /**
     * $_validErrorCorrectionLevels - the valid error corrections levels that are accepted
     *
     * @var array
     */
    private static $_validErrorCorrectionLevels = array(
        'L',
        'M',
        'Q',
        'H',
    );

    /**
     * __construct() - create a qr code
     *
     * @param int    $sized
     * @param string $data
     * @param string $outputEncoding
     * @param string $errorCorrectionLevel
     * @param int    $margin
     * @return void
     */
    public function __construct($size, $data, $outputEncoding = null,
                                $errorCorrectionLevel = null, $margin = null)
    {
        $this->setSize($size);

        $this->setData($data);

        if (null !== $outputEncoding) {
            $this->setOutputEncoding($outputEncoding);
        }

        if (null !== $errorCorrectionLevel) {
            $this->setErrorCorrectionLevel($errorCorrectionLevel);
        }

        if (null !== $margin) {
            $this->setMargin($margin);
        }
    }

    /**
     * setSize() - set the size of the qr code
     *
     * @param int $size
     * @return QrCode Provides a fluent interface
     * @throws Exception
     */
    public function setSize($size)
    {
        if (!ctype_digit((string) $size)) {
            throw new Exception('Size must be of type integer.');
        } elseif (((int) $size < self::MINIMUM_SIZE) || ((int) $size > self::MAXIMUM_SIZE)) {
            throw new Exception('Size must be between ' . self::MINIMUM_SIZE . ' and ' . self::MAXIMUM_SIZE . '.');
        }

        $this->_size = (int) $size;
        return $this;
    }

    /**
     * getSize() - returns the size of the qr code
     *
     * @return int
     */
    public function getSize()
    {
        return (int) $this->_size;
    }

    /**
     * setData() - sets the data to be encoded
     *
     * @param string $data
     * @return QrCode Provides a fluent interface
     */
    public function setData($data)
    {
        $this->_data = (string) $data;
        return $this;
    }

    /**
     * getData() - gets the encoded data of the qr code
     *
     * @return string
     */
    public function getData()
    {
        return (string) $this->_data;
    }

    /**
     * setOutputEncoding() - sets the output encoding if valid
     *
     * @param string $outputEncoding
     * @return QrCode Provides a fluent interface
     * @throws Exception
     */
    public function setOutputEncoding($outputEncoding)
    {
        if (!in_array($outputEncoding, self::$_validOutputEncodings)) {
            throw new Exception('Output encoding must be a valid value.');
        }
        $this->_outputEncoding = (string) $outputEncoding;
        return $this;
    }

    /**
     * getOutputEncoding() - gets the output encoding
     *
     * @return null|string
     */
    public function getOutputEncoding()
    {
        return $this->_outputEncoding;
    }

    /**
     * setErrorCorrectionLevel() - sets the error correction level if valid
     *
     * @param string $errorCorrectionLevel
     * @return QrCode Provides a fluent interface
     * @throws Exception
     */
    public function setErrorCorrectionLevel($errorCorrectionLevel)
    {
        if (!in_array($errorCorrectionLevel, self::$_validErrorCorrectionLevels)) {
            throw new Exception('Error correction level must be a valid value.');
        }
        $this->_errorCorrectionLevel = (string) $errorCorrectionLevel;
        return $this;
    }

    /**
     * getErrorCorrectionLevel() - gets the error correction level
     *
     * @return null|string
     */
    public function getErrorCorrectionLevel()
    {
        return $this->_errorCorrectionLevel;
    }

    /**
     * setMargin() - sets the margin of the qr code
     *
     * @param int $margin
     * @return QrCode Provides a fluent interface
     * @throws Exception
     */
    public function setMargin($margin)
    {
        if (!ctype_digit((string) $margin)) {
            throw new Exception('Margin must be of type integer.');
        }
        $this->_margin = (int) $margin;
        return $this;
    }

    /**
     * getMargin() - returns the margin of the qr code
     *
     * @return null|int
     */
    public function getMargin()
    {
        return $this->_margin;
    }

    /**
     * getQrCodeUrl() - returns the url that generates the qr code
     *
     * @return string
     */
    public function getUrl()
    {
        $url = 'http://chart.googleapis.com/chart?cht=qr';

        $url .= '&chs=' . $this->getSize() . 'x' . $this->getSize();
        $url .= '&chl=' . $this->getData();

        if (null !== ($outputEncoding = $this->getOutputEncoding())) {
            $url .= '&choe=' . $outputEncoding;
        }

        // max() is used to execute both clauses so both variables get assigned
        if (max(null !== ($errorCorrectionLevel = $this->getErrorCorrectionLevel()),
                null !== ($margin = $this->getMargin())))
        {
            $url .= '&chld=';

            if (null !== $errorCorrectionLevel) {
                $url .= $errorCorrectionLevel;
            }

            if (null !== $margin) {
                $url .= '|' . $margin;
            }
        }

        return (string) $url;
    }

}
