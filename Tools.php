<?php
function int_ok($n) {
	if(!is_int($n)) {
		throw new Exception($n.' n\'est pas un entier !');
	}
}

function domain_ok($domain) {
	if(!$domain instanceof Domain) {
		throw new Exception($n.' n\'est pas un Domain !');
	}
}
?>