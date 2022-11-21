<?php
/**
 * Component template for a form
 *
 * @package Perique\form-fields
 *
 * // Expected Variables
 * @var string $key The key of the form.
 * @var string $method The method of the form.
 * @var string $action The action of the form.
 * @var string|null $enctype The enctype of the form.
 * @var string|null $autocomplete The autocomplete of the form.
 * @var bool        $novalidate The novalidate of the form.
 * @var string|null $target The target of the form.
 * @var string|null $accept_charset The accept_charset of the form.
 * @var string      $attributes The attributes of the form.
 * @var array       $fields The fields of the form.
 * @var array       $values The values of the form.
 * @var string|null $nonce_action The nonce_action of the form.
 */

use PinkCrab\Form\Component\Form\Partial\Nonce_Component;

?>

<?php if ( ! empty( $fields ) ) : ?>
	<form
		id="<?php echo esc_attr( $key ); ?>"
		method="<?php echo esc_attr( $method ); ?>"
		action="<?php echo esc_attr( $action ); ?>"
		<?php echo $attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	>
		<input type="hidden" name="pc_form_submitted" value="<?php echo esc_attr( $key ); ?>" />
		
		<?php if ( ! is_null( $nonce_action ) ) : ?>
			<?php echo $this->component( new Nonce_Component( $nonce_action, $nonce_action ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		<?php endif; ?>
		
		<?php foreach ( $fields as $field ) : ?>
			<?php echo $this->component( $field ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		<?php endforeach; ?>
	</form>
<?php endif; ?>
