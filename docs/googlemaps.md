# googlemaps

Embeds [Google Maps](https://maps.google.com).

`{googlemaps query [attr="value"] ...}`

- `query`: The address or coordinates to display on the map (e.g., "1600 Amphitheatre Parkway, Mountain View, CA" or "48.8584,2.2945"). This attribute can also be provided as the content between the shortcode tags or as a positional argument. This attribute is required.
- `zoom`: The zoom level of the map. Defaults to `21`.
- `type`: The map type. Can be `roadmap` (default), `satellite`, `hybrid`, or `terrain`.
- `width`: The width of the embed. Defaults to `100%`.
- `height`: The height of the embed. Defaults to `var(--embed-map-height, 50vh)`.
- `id`: A CSS id for the container `div`.
- `class`: A CSS class for the container `div`.

In addition to the attributes listed, any other standard `<iframe>` attributes (e.g., `title`, `loading`, `sandbox`, `referrerpolicy`) can be provided and will be passed directly to the generated `<iframe>` element.

Note that `width`, `height`, `id`, and `class` are applied to the wrapper `div` and not the `iframe` itself.

## Examples

```
{googlemaps query="Eiffel Tower"}
```

```
{googlemaps "Eiffel Tower" zoom=15 type=hybrid}
```

```
{googlemaps 48.8584,2.2945 zoom=20}
```

```
{googlemaps type=satellite}1600 Amphitheatre Parkway, Mountain View, CA{/googlemaps}
```

## Styling `height`

The `height` attribute defaults to `var(--embed-map-height, 50vh)`, allowing for easy customization via CSS. You can override this variable in your stylesheet:

```css
/* Custom height for all embedded maps */
:root {
  --embed-map-height: 400px;
}

/* Or target specific maps by their container class or ID */
.embed-googlemaps {
  --embed-map-height: 75vh;
}
```
