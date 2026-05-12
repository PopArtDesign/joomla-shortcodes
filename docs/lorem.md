# lorem

Generates ["Lorem Ipsum"](https://en.wikipedia.org/wiki/Lorem_ipsum) placeholder content.

`{lorem [tag] [count] [attr="value"] ...}`

- `tag`: (Optional) The HTML tag to generate (e.g., `p`, `div`, `ul`, `ol`, `img`).
  - If not provided, generates plain Lorem Ipsum text.
  - If a `class` attribute is provided without a `tag`, it defaults to `p`.
- `count`: (Optional) The number of times to repeat the `tag`. Can be a single number or a range (e.g., `3,5` for a random number between 3 and 5). Defaults to `1`.
  - If `tag` is `ul` or `ol`, this count specifies the number of `<li>` items to generate within the list.
- `words`: (Optional) The number of words to generate per item/tag. You can specify a single number or a range (e.g., `words=50,100`). Defaults to `84` (full default text).
- `class`: (Optional) A CSS class to apply to the generated tag (e.g., `my-class`).
- `alt`: (Optional, for `img` tag) The `alt` text for the `<img>` tag.

**Specific to `img` tag:**

When `tag` is `img`, the shortcode generates an inline Base64-encoded placeholder image using the GD library.

- `width`: The width of the image in pixels. Defaults to `150`.
- `height`: The height of the image in pixels. Defaults to `150`.
- `class`: (Optional) A CSS class to apply to the `<img>` tag.
- `alt`: (Optional) The `alt` text for the `<img>` tag.

## Examples

Generate the full default Lorem Ipsum text without any wrapping tags:
```
{lorem}
```

Generate 50 words of Lorem Ipsum text without any wrapping tags:
```
{lorem words=50}
```

Generate a single paragraph (`<p>`) containing a random number of words (between 20 and 30) of Lorem Ipsum text:
```
{lorem p words=20,30}
```

Generate three paragraphs (`<p>`) each containing the full Lorem Ipsum text:
```
{lorem p 3}
```

Generate an unordered list (`<ul class="my-list">`) with 3 to 5 list items (`<li>`), where each list item contains 5 to 10 words of Lorem Ipsum text:
```
{lorem ul 3,5 words=5,10 class="my-list"}
```

Generate an inline placeholder image with dimensions 300x200 pixels, a class `img-fluid`, and alt text "Placeholder image":
```
{lorem img width=300 height=200 class="img-fluid" alt="Placeholder image"}
```

Generate the full default Lorem Ipsum text wrapped in a `<p class="text-center">` tag:
```
{lorem class="text-center"}
```
