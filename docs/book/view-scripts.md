# View Scripts

Once you call `render()`, `Laminas\View\Renderer\PhpRenderer` then `include()`s the
requested view script and executes it "inside" the scope of the `PhpRenderer`
instance. Therefore, in your view scripts, references to `$this` actually point
to the `PhpRenderer` instance itself.

Variables assigned to the view, either via a [View Model](quick-start.md#controllers-and-view-models),
[Variables container](quick-start.md), or by passing an array of variables to
`render()`, may be retrieved in three ways:

- Explicitly, by retrieving them from the `Variables` container composed in the
  `PhpRenderer`: `$this->vars()->varname`.
- As instance properties of the `PhpRenderer` instance: `$this->varname`. (In
  this situation, instance property access is proxying to the composed
  `Variables` instance.)
- As local PHP variables: `$varname`. The `PhpRenderer` extracts the members of
  the `Variables` container locally.

We generally recommend using the second notation, as it's less verbose than the
first, but differentiates between variables in the view script scope and those
assigned to the renderer from elsewhere.

By way of reminder, here is the example view script from the `PhpRenderer`
introduction.

```php
<?php if ($this->books): ?>

    <!-- A table of some books. -->
    <table>
        <tr>
            <th>Author</th>
            <th>Title</th>
        </tr>

        <?php foreach ($this->books as $key => $val): ?>
        <tr>
            <td><?= $this->escapeHtml($val['author']) ?></td>
            <td><?= $this->escapeHtml($val['title']) ?></td>
        </tr>
        <?php endforeach; ?>

    </table>

<?php else: ?>

    <p>There are no books to display.</p>

<?php endif;?>
```

> TIP: **IDE Auto-Completion in View Scripts**
> The `Laminas\View\Renderer\PhpRenderer` class can be used to provide auto-completion for modern IDEs.
> It defines the aliases of the view helpers in a DocBlock as `@method` tags.
>
> ### Usage
>
> In order to allow auto-completion in view scripts, `$this` variable should be type-hinted via a DocBlock at the top of a view script.
> It is recommended that always the `Laminas\View\Renderer\PhpRenderer` is added as the first type, so that the IDE can auto-suggest the default view helpers from `laminas-view`:
>
> ```php
> /**
>  * @var Laminas\View\Renderer\PhpRenderer $this
>  */
> ```
> The different Laminas components that contain view helpers provide `HelperTrait` traits with more aliases of the view helpers.
> These traits can be chained with a pipe symbol (a.k.a. vertical bar) `|` as many as needed, depending on which view helpers from the different Laminas component are used and where the auto-completion is to be made.

## Escaping Output

One of the most important tasks to perform in a view script is to make sure that
output is escaped properly; among other things, this helps to avoid cross-site
scripting attacks. Unless you are using a function, method, or helper that does
escaping on its own, you should always escape variables when you output them and
pay careful attention to applying the correct escaping strategy to each HTML
context you use.

The `PhpRenderer` includes a selection of helpers you can use for this purpose:
`EscapeHtml`, `EscapeHtmlAttr`, `EscapeJs`, `EscapeCss`, and `EscapeUrl`.
Matching the correct helper (or combination of helpers) to the context into
which you are injecting untrusted variables will ensure that you are protected
against Cross-Site Scripting (XSS) vulnerabilities.

```php
// bad view-script practice:
echo $this->variable;

// good view-script practice:
echo $this->escapeHtml($this->variable);

// and remember context is always relevant!
<script type="text/javascript">
    var foo = "<?= $this->escapeJs($variable) ?>";
</script>
```
