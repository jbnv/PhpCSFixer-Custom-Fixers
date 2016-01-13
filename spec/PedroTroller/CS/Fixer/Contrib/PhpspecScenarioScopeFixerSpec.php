<?php

namespace spec\PedroTroller\CS\Fixer\Contrib;

use PhpSpec\ObjectBehavior;

class PhpspecScenarioScopeFixerSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('PedroTroller\CS\Fixer\Contrib\PhpspecScenarioScopeFixer');
    }

    function it_returns_its_name()
    {
        $this->getName()->shouldReturn('phpspec-scenario-scope');
    }

    function it_fix_spec_file(\SplFileInfo $spl)
    {
        $spec = <<<SPEC
<?php

namespace spec\Project\Namespace;

use PhpSpec\ObjectBehavior;

class TheSpec extends ObjectBehavior
{
    public function let(\$file) {
        return;
    }

    public function letGo(\$file) {
        return;
    }

    public function it_is_a_spec(\$file) {
        return;
    }

    public function itIsNotASpec(\$file) {
        return;
    }
}
SPEC;

        $expect = <<<SPEC
<?php

namespace spec\Project\Namespace;

use PhpSpec\ObjectBehavior;

class TheSpec extends ObjectBehavior
{
    function let(\$file) {
        return;
    }

    function letGo(\$file) {
        return;
    }

    function it_is_a_spec(\$file) {
        return;
    }

    public function itIsNotASpec(\$file) {
        return;
    }
}
SPEC;

        $this->fix($spl, $spec)->shouldReturn($expect);
    }

    function it_doesnt_fix_other_classes(\SplFileInfo $spl)
    {
        $spec = <<<SPEC
<?php

namespace spec\Project\Namespace;

class TheSpec
{
    public function let(\$file) {
        return;
    }

    public function letGo(\$file) {
        return;
    }

    public function it_is_a_spec(\$file) {
        return;
    }

    public function itIsNotASpec(\$file) {
        return;
    }
}
SPEC;

        $this->fix($spl, $spec)->shouldReturn($spec);
    }
}