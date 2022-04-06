<div class="jumbotron" style="width:100%;" >
	<p style="color:green;font-size:large;text-align:left"><b>Συνεντεύξεις </b></p>
	<br>
	<div class="video-responsive" style="padding-right:10%;" align="center">
		<iframe width="100%" height="100%" class="lazyload" loading="lazy" src="https://www.youtube.com/embed/XguG6ZO56hc" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
	</div>
</div>

<script>
  if ('loading' in HTMLIFrameElement.prototype) {
    const iframes = document.querySelectorAll('iframe[loading="lazy"]');

    iframes.forEach(iframe => {
      iframe.src = "https://www.youtube.com/embed/XguG6ZO56hc";
    });

  } else {
    // Dynamically import the LazySizes library
    const script = document.createElement('script');
    script.src =
      'https://cdnjs.cloudflare.com/ajax/libs/lazysizes/5.2.2/lazysizes.min.js';
    document.body.appendChild(script);
  }

</script>
