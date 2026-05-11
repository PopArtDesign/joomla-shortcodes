# pdf

Embeds PDF documents.

`{pdf url [attr="value"] ...}`

-   `url`: The URL to the PDF document (e.g., `https://example.com/document.pdf`). Required if not using nested content.
-   `width`: The width of the embed. Defaults to `100%`.
-   `height`: The height of the embed. Defaults to `75vh`.
-   `id`: A CSS id for the container `div`.
-   `class`: A CSS class for the container `div`.
-   `title`: The `title` attribute for the `object`.

## Examples

```
{pdf https://example.com/document.pdf}
```

```
{pdf /another.pdf width=600 height=800}
```

## Styling `height`

The `height` attribute defaults to `var(--embed-pdf-height, 75vh)`, allowing for easy customization via CSS. You can override this variable in your stylesheet:

```css
/* Custom height for all embedded PDFs */
:root {
  --embed-pdf-height: 400px;
}

/* Or target specific PDFs by their container class or ID */
.embed-pdf {
  --embed-pdf-height: 75vh;
}
```
