<?php

declare( strict_types=1 );

/**
 * The base of all <Input> fields.
 *
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * @author Glynn Quelch <glynn.quelch@gmail.com>
 * @license http://www.opensource.org/licenses/mit-license.html  MIT License
 * @package PinkCrab\Form
 */

namespace PinkCrab\Form\Builder\Element\Field\Input;

use PinkCrab\Form\Utils;
use PinkCrab\Form\Builder\Element\Field;
use PinkCrab\Form\Builder\Element\Field\Attribute\Label;
use PinkCrab\Form\Builder\Element\Field_Traits\Form_Style;
use PinkCrab\Form\Builder\Element\Field\Attribute\Notification;
use PinkCrab\Form\Builder\Element\Field\Attribute\Single_Value;

class Abstract_Input extends Field {

	use Label, Single_Value, Form_Style, Notification;

	/**
	 * Set the value of the input
	 *
	 * @param mixed $value
	 * @return void
	 */
	public function set_existing( $value ): void {
		// If this input has a single value.
		if ( Utils::class_uses_trait( $this, Single_Value::class ) ) {
			$this->value( $value );
		}
	}

	/**
	 * Sets the input type
	 *
	 * @var string
	 */
	protected $input_type = 'text';

	/**
	 * Get the input type
	 *
	 * @return string
	 */
	public function get_input_type(): string {
		return $this->input_type;
	}

	/**
	 * @inheritDoc
	 */
	public function get_type(): string {
		return $this->get_input_type() . '_input';
	}
}
