<?php

function get_url( $path, $query = [] ) {
	$query = $query ? '?' . http_build_query($query) : '';
	$path = $path ? $path . '.php' : basename($_SERVER['SCRIPT_NAME']);
	return $path . $query;
}

function do_redirect( $path = null, $query = [] ) {
	$url = get_url($path, $query);
	header('Location: ' . $url);
	exit;
}

function html_options( $options, $selected = null, $empty = '', $datalist = false ) {
	$selected = (array) $selected;

	$html = '';
	$empty && $html .= '<option value="">' . $empty . '</option>';
	foreach ( $options AS $value => $label ) {
		if ( is_array($label) ) {
			$html .= '<optgroup label="' .  html($value) . '">';
			foreach ( $label as $value2 => $label2) {
				$isSelected = in_array($value2, $selected) ? ' selected' : '';
				$html .= '<option value="' . html($value2) . '"' . $isSelected . '>' . html($label2) . '</option>';
			}
			$html .= '</optgroup>';
		}
		else {
			$isSelected = in_array($value, $selected) ? ' selected' : '';
			$value = $datalist ? html($label) : html($value);
			$label = $datalist ? '' : html($label);
			$html .= '<option value="' . $value . '"' . $isSelected . '>' . $label . '</option>';
		}
	}
	return $html;
}

function html( $text ) {
	return htmlspecialchars((string)$text, ENT_QUOTES, 'UTF-8') ?: htmlspecialchars((string)$text, ENT_QUOTES, 'ISO-8859-1');
}
