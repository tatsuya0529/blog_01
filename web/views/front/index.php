{% extends front_template %}

{% block content %}
	<h1>Twig</h1>
{% for article in articles %}
	<p>
		{{article.title}}
	</p>
{% endfor %}
{% endblock %}
