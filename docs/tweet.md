# tweet

Embeds posts from [X](https://x.com) (formerly Twitter).

`{tweet url [attr="value"] ...}`

- `url`: The full URL of the tweet to embed (e.g., `https://x.com/user/status/12345`). This is required if the URL is not provided as the content of the shortcode.
- `width`: The width of the embed.
- `height`: The height of the embed.
- `id`: A CSS id for the container `div`.
- `class`: A CSS class for the container `div`.

## Examples

```
{tweet https://x.com/WordPress/status/1868689630931059186}
```

```
{tweet}https://x.com/WordPress/status/1868689630931059186{/tweet}
```
