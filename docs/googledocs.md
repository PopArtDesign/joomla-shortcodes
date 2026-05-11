# googledocs

Embeds [Google Docs](https://docs.google.com), Sheets, Slides, and other files from [Google Drive](https://workspace.google.com/products/drive).

`{googledocs url [attr="value"] ...}`

-   `url`: The URL to the Google Doc/Sheet/Slide. Required if not using nested content.
-   `width`: The width of the embed. Defaults to `100%`.
-   `height`: The height of the embed. Defaults to `100%`.
-   `id`: A CSS id for the container `div`.
-   `class`: A CSS class for the container `div`.

In addition to the attributes listed, any other standard `<iframe>` attributes (e.g., `title`, `loading`, `sandbox`, `referrerpolicy`) can be provided and will be passed directly to the generated `<iframe>` element.

Note that `width`, `height`, `id`, and `class` are applied to the wrapper `div` and not the `iframe` itself.

## Examples

```
{googledocs}https://docs.google.com/document/d/a-valid-id/edit{/googledocs}
```

```
{googledocs width=800 height=600}
https://docs.google.com/spreadsheets/d/a-valid-id/edit
{/googledocs}
```
