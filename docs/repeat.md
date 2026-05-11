# repeat

Repeats the enclosed content a specified number of times.

`{repeat N|min,max} ... {/repeat}`

-   `N|min,max`: The number of times to repeat the content. You can specify a single number or a range for a random number of repetitions (e.g., `3,5`). Defaults to `1`.

## Examples

```
{repeat 3}Hello, World! {/repeat}
```

This will output: `Hello, World! Hello, World! Hello, World!`

```
{repeat 1,3}Beetlejuice! {/repeat}
```

This will output "Beetlejuice! " one, two, or three times randomly.
