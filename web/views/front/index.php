{% extends template %}

{% block content %}
	<h1>ブログっ！！</h1>
	<div>
		<p><a href="/create">新規投稿</a></p>
	</div>
	<hr>
{% for article in articles %}
	<div>
		<p>{{article.created_at}}</p>
		<p>{{article.title}}</p>
		<p>{{article.content}}</p>
		<p><a href="/create/{{article.id}}">編集</a></p>
	</div>
	<hr>
{% endfor %}
{% endblock %}
