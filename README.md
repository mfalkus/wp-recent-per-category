# wp-recent-per-category

Pretty much the WordPress Recent Posts plugin, but with the option to only show posts in a specific category.

This plugin was the result of wanting to split some of my non-technical posts out of the home page widget I use
for recent articles I've put online. I figured two wigets was the answer, one for technical, one for non-technical.
WordPress already knows the difference anyway based on the categories I put the posts in, I just needed to utilise
this for the display.

## Installation

As easy as usual.

1. Copy the PHP file to your `plugins` directory.
2. Enable the plugin from the WordPress dashboard.
3. Situate the widget in the widget area as you would with the 'Recent Posts' widget.

## Styling

Note that if you have custom styles in place for the usual recent posts widget you'll probably need to update them to
be applied to this widget. Something like `s/widget_recent_posts/widget_recent_per_category/` should be sufficient.
