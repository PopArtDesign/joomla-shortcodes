# telegram

Embeds posts from [Telegram](https://telegram.org/).

`{telegram url [attr="value"] ...}`

- `url`: The full URL of the Telegram post to embed (e.g., `https://t.me/durov/89`). This is required if the URL is not provided as the content of the shortcode.
- `width`: The width of the embed.
- `height`: The height of the embed.
- `id`: A CSS id for the container `div`.
- `class`: A CSS class for the container `div`.

## Examples

```
{telegram https://t.me/durov/89}
```

```
{telegram}https://t.me/durov/89{/telegram}
```
