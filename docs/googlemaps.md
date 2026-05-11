# googlemaps

Embeds [Google Maps](https://maps.google.com).

`{googlemaps [attr="value"] ...}`

-   `address`: The address to display on the map (e.g., "1600 Amphitheatre Parkway, Mountain View, CA").
-   `lat`, `lon`: The latitude and longitude for the map center. Use instead of `address`.
-   `zoom`: The zoom level of the map. Defaults to `21`.
-   `type`: The map type. Can be `roadmap` (default), `satellite`, `hybrid`, or `terrain`.
-   `width`: The width of the embed. Defaults to `100%`.
-   `height`: The height of the embed. Defaults to `var(--embed-map-height, 50vh)`.
-   `id`: A CSS id for the container `div`.
-   `class`: A CSS class for the container `div`.

Either `address` or both `lat` and `lon` are required.

In addition to the attributes listed, any other standard `<iframe>` attributes (e.g., `title`, `loading`, `sandbox`, `referrerpolicy`) can be provided and will be passed directly to the generated `<iframe>` element.

Note that `width`, `height`, `id`, and `class` are applied to the wrapper `div` and not the `iframe` itself.

**Examples:**

```
{googlemaps address="Eiffel Tower"}
```

```
{googlemaps lat=48.8584 lon=2.2945 zoom=15 type=hybrid}
```
