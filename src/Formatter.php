<?php
namespace dirtsimple\Postmark;

use League\CommonMark\Block;
use League\CommonMark\CommonMarkConverter;
use League\CommonMark\Environment;
use League\CommonMark\Extension;
use League\CommonMark\Inline;

class Formatter {

	protected static $converter;

	static function format($doc, $field, $value) {
		static $converter = null;
		$converter = $converter ?: static::formatter();
		$markdown = apply_filters('postmark_markdown', $value, $doc, $field);
		$html = $converter->convertToHtml($markdown);
		return apply_filters('postmark_html', $html, $doc, $field);
	}

	protected static function formatter() {
		$cfg = array(
			'renderer' => array(
				'block_separator' => "",
				'inner_separator' => "",
				'line_break' => "",
			),
			'extensions' => array(
				'League\CommonMark\Ext\Table\TableExtension' => null,
				'Webuni\CommonMark\AttributesExtension\AttributesExtension' => null,
				'League\CommonMark\Ext\Strikethrough\StrikethroughExtension' => null,
				'League\CommonMark\Ext\SmartPunct\SmartPunctExtension' => null,
				'dirtsimple\Postmark\ShortcodeParser' => null,
			),
		);
		$env = Environment::createCommonMarkEnvironment();
		$cfg = apply_filters('postmark_formatter_config', $cfg, $env);
		return new CommonMarkConverter($cfg, $env);
	}
}
