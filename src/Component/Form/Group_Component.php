<?php

declare( strict_types=1 );

/**
 * Component for rendering a group of fields
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

namespace PinkCrab\Form\Component\Form;

use PinkCrab\Form\Utils;
use PinkCrab\Perique\Services\View\Component\Component;
use function PinkCrab\FunctionConstructors\Objects\isInstanceOf;

class Group_Component extends Component {

	/**
	 * Collection of components
	 *
	 * @var Component[]
	 */
	protected $components = array();

	/**
	 * String of attributes to be added to the group
	 *
	 * @var string
	 */
	protected $attributes;

	/**
	 * Before the fields.
	 *
	 * @var string
	 */
	protected $before;

	/**
	 * After the fields.
	 *
	 * @var string
	 */
	protected $after;

	/**
	 * Creates an instance of the component.
	 *
	 * @param array<Component> $components
	 * @param string $attributes
	 */
	public function __construct(
		array $components,
		string $attributes = '',
		string $before = '',
		string $after = ''
	) {
		$this->attributes = $attributes;
		$this->before     = $before;
		$this->after      = $after;

		$this->components = array_filter( $components, isInstanceOf( Component::class ) ); // @phpstan-ignore-line, 
		// Strange false positive, Parameter #2 $callback of function array_filter expects callable(PinkCrab\Perique\Services\View\Component\Component): mixed,  Closure('PinkCrab\\Perique\\Services\\View\\Component\\Component'): bool given.
	}
}
