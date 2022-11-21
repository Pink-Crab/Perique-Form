<?php

declare( strict_types=1 );

/**
 * General purpose functions.
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

namespace PinkCrab\Form;

use PinkCrab\Form\Builder\Element\Field;
use PinkCrab\Form\Component\Field\Input_Component;
use PinkCrab\Form\Builder\Element\Field\Input\Abstract_Input;

class Utils {

	/**
	 * Gets all traits up the hierarchy of a class.
	 *
	 * @param class-string|object $class
	 * @return array<string>
	 */
	public static function get_all_traits( $class ): array {
		$traits = array();

		// Get traits of all parent classes
		do {
			$traits = array_merge( class_uses( $class, true ) ?: array(), $traits );
		} while ( $class = get_parent_class( $class ) );

		// Get traits of all parent traits
		$traits_to_search = $traits;
		while ( ! empty( $traits_to_search ) ) {
			$new_traits       = class_uses( array_pop( $traits_to_search ), true ) ?: array();
			$traits           = array_merge( $new_traits, $traits );
			$traits_to_search = array_merge( $new_traits, $traits_to_search );
		}

		return array_unique( $traits );
	}

	/**
	 * Check if a class uses a trait.
	 *
	 * @param class-string|object $class
	 * @param string $trait
	 * @return bool
	 */
	public static function class_uses_trait( $class, string $trait ): bool {
		return in_array( $trait, self::get_all_traits( $class ), true );
	}

	/**
	 * Maps a form field to a component type.
	 *
	 * @param Field $field
	 * @return string class-string<Abstract_Field_Component>
	 * @throws \Exception If unable to map to a component.
	 */
	public static function map_field_to_component( Field $field ): string {
		switch ( true ) {
			// Is an Input Field.
			case $field instanceof Abstract_Input:
				return Input_Component::class;

			default:
				throw new \Exception( sprintf( '%s has no valid component.', get_class( $field ) ), 1 );
		}
	}

	/**
	 * Merge 2 sets of arrays.
	 *
	 * Can set some keys to concatenate the values.
	 *
	 * @param array<string, string> $attributes
	 * @param array<string, string> $new_attributes
	 * @param array<string> $concat_keys
	 * @return array<string, string>
	 */
	public static function merge_arrays( array $attributes, array $new_attributes, array $concat_keys = array() ): array {
		foreach ( $new_attributes as $key => $value ) {

			if ( array_key_exists( $key, $attributes )
			&& in_array( $key, $concat_keys, true )
			) {
				$attributes[ $key ] .= ' ' . $value;
				continue;
			}

			$attributes[ $key ] = $value;
		}
		return $attributes;
	}

	/**
	 * Parses an array of attributes into a string.
	 *
	 * @param array<string, mixed> $attributes
	 * @return string
	 */
	public static function parse_attributes( array $attributes ): string {
		return join(
			' ',
			\PinkCrab\FunctionConstructors\Arrays\mapWithKey(
				function( $key, $value ): string {
					return $value === null
						? esc_attr( strval( $key ) )
						: esc_attr( strval( $key ) ) . '="' . esc_html( strval( $value ) ) . '"';
				}
			)( $attributes )
		);
	}

	/**
	 * Esc attribute.
	 *
	 * @param mixed $attribute
	 * @return string
	 */
	public static function esc_attr( $attribute ): string {
		return esc_attr( strval( $attribute ) );
	}

	/**
	 * Esc html.
	 *
	 * @param mixed $html
	 * @return string
	 */
	public static function esc_html( $html ): string {
		return esc_html( strval( $html ) );
	}
}
