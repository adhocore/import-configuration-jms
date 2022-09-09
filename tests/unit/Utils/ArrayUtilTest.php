<?php

/**
 * TechDivision\Import\Configuration\Jms\ConfigurationFactoryTest
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * PHP version 5
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-configuration-jms
 * @link      http://www.techdivision.com
 */

namespace TechDivision\Import\Configuration\Jms\Utils;

use PHPUnit\Framework\TestCase;

/**
 * Test class for the JMS configuration factory.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-configuration-jms
 * @link      http://www.techdivision.com
 */
class ArrayUtilTest extends TestCase
{

    /**
     * The array utility instance.
     *
     * @var \TechDivision\Import\Configuration\Jms\Utils\ArrayUtil
     */
    protected $arrayUtil;

    /**
     * Sets up the fixture, for example, open a network connection.
     * This method is called before a test is executed.
     *
     * @return void
     * @see \PHPUnit\Framework\TestCase::setUp()
     */
    protected function setUp()
    {
        $this->arrayUtil = new ArrayUtil();
    }

    /**
     * Test's the factory() method.
     *
     * @return void
     */
    public function testReplace()
    {

        $main = array(
            'level-01-01' => array(
                'level-02-01' => array(
                    'level-03-01' => array(
                        'level-03-01-val-01',
                        'level-03-01-val-02',
                        'level-03-01-val-03',
                        'level-03-01-val-04',
                        'level-03-01-val-05',
                        'level-03-01-val-06'
                    )
                ),
                'level-02-02' => 'test'
            ),
            'level-01-02' => array(
                'level-01-02-val-01',
                'level-01-02-val-02'
            ),
            'level-01-03' => array(
                'level-01-03-val-01',
                'level-01-03-val-02'
            )
        );

        $replacement = array(
            'level-01-01' => array(
                'level-02-01' => array(
                    'level-03-01' => array(
                        'level-03-01-val-07',
                        'level-03-01-val-08',
                        'level-03-01-val-09',
                    )
                ),
                'level-02-02' => array(
                    'level-02-02-val-01',
                    'level-02-02-val-02',
                )
            ),
            'level-01-03' => 'level-01-03-val-00'
        );

        $expectedResult = array(
            'level-01-01' => array(
                'level-02-01' => array(
                    'level-03-01' => array(
                        'level-03-01-val-07',
                        'level-03-01-val-08',
                        'level-03-01-val-09',
                    )
                ),
                'level-02-02' => array(
                    'level-02-02-val-01',
                    'level-02-02-val-02',
                )
            ),
            'level-01-02' => array(
                'level-01-02-val-01',
                'level-01-02-val-02'
            ),
            'level-01-03' => 'level-01-03-val-00'
        );

        $this->assertSame($expectedResult, $this->arrayUtil->replace($main, $replacement));
    }

    public function testReplaceWithOneArray()
    {

        $main = array(
            'level-01-01' => array(
                'level-02-01' => array(
                    'level-03-01' => array(
                        'level-03-01-val-01',
                        'level-03-01-val-02',
                        'level-03-01-val-03',
                        'level-03-01-val-04',
                        'level-03-01-val-05',
                        'level-03-01-val-06'
                    )
                ),
                'level-02-02' => 'test'
            ),
            'level-01-02' => array(
                'level-01-02-val-01',
                'level-01-02-val-02'
            ),
            'level-01-03' => array(
                'level-01-03-val-01',
                'level-01-03-val-02'
            )
        );

        $this->assertSame($main, $this->arrayUtil->replace($main));
    }/**
    * Test's the factory() method.
    *
    * @return void
    */
    public function testReplaceWithEmptyArray()
    {

        $main = array(
            'level-01-01' => array(
                'level-02-01' => array(
                    'level-03-01' => array(
                        'level-03-01-val-01',
                        'level-03-01-val-02',
                        'level-03-01-val-03',
                        'level-03-01-val-04',
                        'level-03-01-val-05',
                        'level-03-01-val-06'
                    ),
                    'level-03-02' => array(
                        'level-03-02-val-01'
                    )
                ),
                'level-02-02' => 'test'
            ),
            'level-01-02' => array(
                'level-01-02-val-01',
                'level-01-02-val-02'
            ),
            'level-01-03' => array(
                'level-01-03-val-01',
                'level-01-03-val-02'
            )
        );

        $replacement = array(
            'level-01-01' => array(
                'level-02-01' => array(
                    'level-03-01' => array(
                        'level-03-01-val-07',
                        'level-03-01-val-08',
                        'level-03-01-val-09',
                    )
                )
            )
        );

        $expectedResult = array(
            'level-01-01' => array(
                'level-02-01' => array(
                    'level-03-01' => array(
                        'level-03-01-val-07',
                        'level-03-01-val-08',
                        'level-03-01-val-09',
                    ),
                    'level-03-02' => array(
                        'level-03-02-val-01'
                    )
                ),
                'level-02-02' => 'test'
            ),
            'level-01-02' => array(
                'level-01-02-val-01',
                'level-01-02-val-02'
            ),
            'level-01-03' => array(
                'level-01-03-val-01',
                'level-01-03-val-02'
            )
        );

        $this->assertSame($expectedResult, $this->arrayUtil->replace($main, $replacement));
    }
}
