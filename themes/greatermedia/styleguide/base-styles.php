<?php
/**
 * Partial for the Headings section on the Style Guide
 *
 * @package Greater Media
 * @since 0.1.0
 */
 ?>
<div id="base-styles" class="sg-sections">
	<div id="headings" class="sg-content">
		<h2 class="sg-section-title"><?php _e( 'Base Styles', 'greatermedia' ); ?></h2>
	</div>
	<div id="headings" class="sg-section-content">
		<h3 class="sg-subsection-title"><?php _e( 'Headings', 'greatermedia' ); ?></h3>
		<section>
			<h1>H1. The Quick Brown</h1>
			<h2>H2. Fox Jumped Over the Lazy</h2>
			<h3>h3. dog enroute to tanzania</h3>
			<h4>H4. Ainazanat ot Etuorne God</h4>
			<h5>h5. yzal eht revo depmuj xof</h5>
			<h6>H6. Nworb Kciuq eht</h6>
		</section>
		<div class="sg-markup-controls">
			<a href="#" class="sg-btn sg-btn--source">HTML</a>
		</div>
		<div class="sg-source">
			<pre class="prettyprint linenums language-html">
&lt;section&gt;
	&lt;h1&gt;H1. The Quick Brown&lt;/h1&gt;
	&lt;h2&gt;H2. Fox Jumped Over the Lazy&lt;/h2&gt;
	&lt;h3&gt;h3. dog enroute to tanzania&lt;/h3&gt;
	&lt;h4&gt;H4. Ainazanat ot Etuorne God&lt;/h4&gt;
	&lt;h5&gt;h5. yzal eht revo depmuj xof&lt;/h5&gt;
	&lt;h6&gt;H6. Nworb Kciuq eht&lt;/h6&gt;
&lt;/section&gt;
			</pre>
		</div>
	</div>
	<div id="lists" class="sg-section-content">
		<h3 class="sg-subsection-title"><?php _e( 'Lists', 'greatermedia' ); ?></h3>
		<section>
			<dl>
				<dt>Description list, definition term</dt>
				<dd>Description list, description element.</dd>
				<dt>Another term</dt>
				<dd>And another description</dd>
			</dl>

			<ol>
				<li>List Item 1</li>
				<li>List Item 2
					<ol>
						<li>Nested Item 1</li>
						<li>Nested Item 2
							<ol>
								<li>Sub-sub nest 1</li>
								<li>Sub-sub nest 2</li>
							</ol>
						</li>
						<li>Nested item 3</li>
					</ol>
				</li>
				<li>List Item 3</li>
			</ol>

			<ul>
				<li>List Item 1</li>
				<li>List Item 2
					<ul>
						<li>Nested Item 1</li>
						<li>Nested Item 2
							<ul>
								<li>Sub-sub nest 1</li>
								<li>Sub-sub nest 2</li>
							</ul>
						</li>
						<li>Nested item 3</li>
					</ul>
				</li>
				<li>List Item 3</li>
			</ul>
		</section>
		<div class="sg-markup-controls">
			<a href="#" class="sg-btn sg-btn--source">HTML</a>
		</div>
		<div class="sg-source">
			<pre class="prettyprint linenums language-html">
&lt;section&gt;
	&lt;dl&gt;
		&lt;dt&gt;Description list, definition term&lt;/dt&gt;
		&lt;dd&gt;Description list, description element.&lt;/dd&gt;
		&lt;dt&gt;Another term&lt;/dt&gt;
		&lt;dd&gt;And another description&lt;/dd&gt;
	&lt;/dl&gt;

	&lt;ol&gt;
		&lt;li&gt;List Item 1&lt;/li&gt;
		&lt;li&gt;List Item 2
			&lt;ol&gt;
				&lt;li&gt;Nested Item 1&lt;/li&gt;
				&lt;li&gt;Nested Item 2
					&lt;ol&gt;
						&lt;li&gt;Sub-sub nest 1&lt;/li&gt;
						&lt;li&gt;Sub-sub nest 2&lt;/li&gt;
					&lt;/ol&gt;
				&lt;/li&gt;
				&lt;li&gt;Nested item 3&lt;/li&gt;
			&lt;/ol&gt;
		&lt;/li&gt;
		&lt;li&gt;List Item 3&lt;/li&gt;
	&lt;/ol&gt;

	&lt;ul&gt;
		&lt;li&gt;List Item 1&lt;/li&gt;
		&lt;li&gt;List Item 2
			&lt;ul&gt;
				&lt;li&gt;Nested Item 1&lt;/li&gt;
				&lt;li&gt;Nested Item 2
					&lt;ul&gt;
						&lt;li&gt;Sub-sub nest 1&lt;/li&gt;
						&lt;li&gt;Sub-sub nest 2&lt;/li&gt;
					&lt;/ul&gt;
				&lt;/li&gt;
				&lt;li&gt;Nested item 3&lt;/li&gt;
			&lt;/ul&gt;
		&lt;/li&gt;
		&lt;li&gt;List Item 3&lt;/li&gt;
	&lt;/ul&gt;
&lt;/section&gt;
			</pre>
		</div>
	</div>
	<div id="misc-typography" class="sg-section-content">
		<h3 class="sg-subsection-title"><?php _e( 'Miscellaneous Typography', 'greatermedia' ); ?></h3>
		<section>
			<p>Lorem <sup>superscript</sup> dolor
				<sub>subscript</sub> amet, consectetuer adipiscing elit. Nullam dignissim convallis est. Quisque aliquam.
				<cite>cite</cite>. Nunc iaculis suscipit dui. Nam sit amet sem. Aliquam libero nisi, imperdiet at, tincidunt nec, gravida vehicula, nisl. Praesent mattis, massa quis luctus fermentum, turpis mi volutpat justo, eu volutpat enim diam eget metus. Maecenas ornare tortor. Donec sed tellus eget sapien fringilla nonummy.
				<abbr title="National Basketball Association">NBA</abbr> Mauris a ante. Suspendisse quam sem, consequat at, commodo vitae, feugiat in, nunc. Morbi imperdiet augue quis tellus.
				<abbr title="Avenue">AVE</abbr></p>
			<blockquote>
				"This stylesheet is going to help so freaking much."
			</blockquote>
		</section>
		<div class="sg-markup-controls">
			<a href="#" class="sg-btn sg-btn--source">HTML</a>
		</div>
		<div class="sg-source">
			<pre class="prettyprint linenums language-html">
&lt;section&gt;
	&lt;p&gt;Lorem &lt;sup&gt;superscript&lt;/sup&gt; dolor
	&lt;sub&gt;subscript&lt;/sub&gt; amet, consectetuer adipiscing elit. Nullam dignissim convallis est. Quisque aliquam.
	&lt;cite&gt;cite&lt;/cite&gt;. Nunc iaculis suscipit dui. Nam sit amet sem. Aliquam libero nisi, imperdiet at, tincidunt nec, gravida vehicula, nisl. Praesent mattis, massa quis luctus fermentum, turpis mi volutpat justo, eu volutpat enim diam eget metus. Maecenas ornare tortor. Donec sed tellus eget sapien fringilla nonummy.
	&lt;abbr title="National Basketball Association"&gt;NBA&lt;/abbr&gt; Mauris a ante. Suspendisse quam sem, consequat at, commodo vitae, feugiat in, nunc. Morbi imperdiet augue quis tellus.
	&lt;abbr title="Avenue"&gt;AVE&lt;/abbr&gt;&lt;/p&gt;
	&lt;blockquote&gt;
		"This stylesheet is going to help so freaking much."
	&lt;/blockquote&gt;
&lt;/section&gt;
			</pre>
		</div>
	</div>
	<div id="paragraph" class="sg-section-content">
		<h3 class="sg-subsection-title"><?php _e( 'Paragraph', 'greatermedia' ); ?></h3>
		<section>
			<img style="width:250px;height:150px;" src="http://placekitten.com/250/150" alt="Cute Kitten" class="alignright" />
			<p>Lorem ipsum dolor sit amet,
				<a href="#" title="test link">test link</a> adipiscing elit. Nullam dignissim convallis est. Quisque aliquam. Donec faucibus. Nunc iaculis suscipit dui. Nam sit amet sem. Aliquam libero nisi, imperdiet at, tincidunt nec, gravida vehicula, nisl. Praesent mattis, massa quis luctus fermentum, turpis mi volutpat justo, eu volutpat enim diam eget metus. Maecenas ornare tortor. Donec sed tellus eget sapien fringilla nonummy. Mauris a ante. Suspendisse quam sem, consequat at, commodo vitae, feugiat in, nunc. Morbi imperdiet augue quis tellus.
			</p>
			<p>Lorem ipsum dolor sit amet,
				<em>emphasis</em> consectetuer adipiscing elit. Nullam dignissim convallis est. Quisque aliquam. Donec faucibus. Nunc iaculis suscipit dui. Nam sit amet sem. Aliquam libero nisi, imperdiet at, tincidunt nec, gravida vehicula, nisl. Praesent mattis, massa quis luctus fermentum, turpis mi volutpat justo, eu volutpat enim diam eget metus. Maecenas ornare tortor. Donec sed tellus eget sapien fringilla nonummy. <strong>Strong</strong> mauris a ante. Suspendisse quam sem, consequat at, commodo vitae, feugiat in, nunc. Morbi imperdiet augue quis tellus.
			</p>
		</section>
		<div class="sg-markup-controls">
			<button class="sg-btn sg-btn--source">HTML</button>
		</div>
		<div class="sg-source">
			<pre class="prettyprint linenums language-html">
&lt;section&gt;
	&lt;img style="width:250px;height:150px;" src="http://placekitten.com/250/150" alt="Cute Kitten" class="alignright" /&gt;
	&lt;p&gt;Lorem ipsum dolor sit amet, &lt;a href="#" title="test link"&gt;test link&lt;/a&gt; adipiscing elit. Nullam dignissim convallis est. Quisque aliquam. Donec faucibus. Nunc iaculis suscipit dui. Nam sit amet sem. Aliquam libero nisi, imperdiet at, tincidunt nec, gravida vehicula, nisl. Praesent mattis, massa quis luctus fermentum, turpis mi volutpat justo, eu volutpat enim diam eget metus. Maecenas ornare tortor. Donec sed tellus eget sapien fringilla nonummy. Mauris a ante. Suspendisse quam sem, consequat at, commodo vitae, feugiat in, nunc. Morbi imperdiet augue quis tellus.
	&lt;/p&gt;
	&lt;p&gt;Lorem ipsum dolor sit amet, &lt;em&gt;emphasis&lt;/em&gt; consectetuer adipiscing elit. Nullam dignissim convallis est. Quisque aliquam. Donec faucibus. Nunc iaculis suscipit dui. Nam sit amet sem. Aliquam libero nisi, imperdiet at, tincidunt nec, gravida vehicula, nisl. Praesent mattis, massa quis luctus fermentum, turpis mi volutpat justo, eu volutpat enim diam eget metus. Maecenas ornare tortor. Donec sed tellus eget sapien fringilla nonummy. &lt;strong&gt;Strong&lt;/strong&gt; mauris a ante. Suspendisse quam sem, consequat at, commodo vitae, feugiat in, nunc. Morbi imperdiet augue quis tellus.
	&lt;/p&gt;
&lt;/section&gt;
			</pre>
		</div>
	</div>
</div>