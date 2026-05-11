# gist

Embeds [GitHub Gists](gist.github.com).

`{gist url [attr="value"] ...}`

-   `url`: The GitHub Gist URL (e.g., `https://gist.github.com/user/12345`). Required if not using nested content.
-   `file`: Specifies a particular file from the Gist to embed.
-   `width`: The width of the embed. Defaults to `100%`.
-   `height`: The height of the embed. Defaults to `100%`.
-   `id`: A CSS id for the container `div`.
-   `class`: A CSS class for the container `div`.

**Examples:**

```
{gist https://gist.github.com/user/12345}
```

```
{gist https://gist.github.com/user/12345 file=example.php}
```
