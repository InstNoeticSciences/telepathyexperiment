{if $logged_user}
	<h1>API documentation</h1>
{else}
	<h1 class='header'>API documentation</h1>
{/if}
<h2>Message methods</h2>

<h3>Public</h3>
<p>Returns 20 latest public messages written by any user in the desired format</p>
<p>
<strong>URL: </strong><pre>{$base_href}api/messages/public.[format].[api_key]</pre><br />
<strong>Formats: </strong>xml, json, rss
</p>

<h3>Friends</h3>
<p>Returns specified user's friends' messages in the desired format.</p>
<p>
<strong>URL: </strong><pre>{$base_href}api/messages/friends/[user].[format].[api_key]</pre><br />
<strong>Formats: </strong>xml, json, rss
</p>

<h3>User</h3>
<p>Returns all public messages of the specified user in the desired format.</p>
<p>
<strong>URL: </strong><pre>{$base_href}api/messages/user/[user].[format].[api_key]</pre><br />
<strong>Formats: </strong>xml, json, rss
</p>

<h3>Latest</h3>
<p>Returns specified user's latest public message in the desired format.</p>
<p>
<strong>URL: </strong><pre>{$base_href}api/messages/latest/[user].[format].[api_key]</pre><br />
<strong>Formats: </strong>xml, json, rss
</p>

<h3>Send</h3>
<p>Posts a public message. Returns its details or an error message in the desired format.</p>
<p>
<strong>URL: </strong><pre>{$base_href}api/messages/send.[format].[api_key]</pre><br />
<strong>Method: </strong><pre>POST</pre><br />
<strong>Required parameters: </strong><br />
<ul>
<li><pre>username</pre> (authenticating user's username or ID)</li>
<li><pre>password</pre> (authenticating user's password)</li>
<li><pre>message</pre> (message content)</li>
</ul>
<strong>Formats: </strong>xml, json
</p>

<h3>Delete</h3>
<p>Removes authenticating user's message. Returns deleted message's details or an error message in the desired format.</p>
<p>
<strong>URL: </strong><pre>{$base_href}api/messages/delete.[format].[api_key]</pre><br />
<strong>Method: </strong><pre>POST</pre><br />
<strong>Required parameters: </strong><br />
<ul>
<li><pre>username</pre> (authenticating user's username or ID)</li>
<li><pre>password</pre> (authenticating user's password)</li>
<li><pre>message_id</pre> (message ID)</li>
</ul>
<strong>Formats: </strong>xml, json
</p>

<h2>Direct message methods</h2>

<h3>Messages</h3>
<p>Returns all direct messages that are in the authenticating user's inbox in the desired format.</p>
<p>
<strong>URL: </strong><pre>{$base_href}api/direct_messages/messages.[format].[api_key]</pre><br />
<strong>Method: </strong><pre>POST</pre><br />
<strong>Required parameters: </strong><br />
<ul>
<li><pre>username</pre> (authenticating user's username)</li>
<li><pre>password</pre> (authenticating user's password)</li>
</ul>
<strong>Formats: </strong>xml, json, rss
</p>

<h3>Send</h3>
<p>Posts a direct message to a user specified by their username or ID. Returns the sent message's details in the desired format or an error message in case of an error.</p>
<p>
<strong>URL: </strong><pre>{$base_href}api/direct_messages/send.[format].[api_key]</pre><br />
<strong>Method: </strong><pre>POST</pre><br />
<strong>Required parameters: </strong><br />
<ul>
<li><pre>username</pre> (authenticating user's username)</li>
<li><pre>password</pre> (authenticating user's password)</li>
<li><pre>message</pre> (username or ID of the recipient of the direct message)</li>
</ul>
<strong>Optional parameters: </strong><br />
<ul>
<li><pre>reply</pre> (ID of a message, to which the current message is a reply)</li>
<li><pre>direct</pre> (recipient username or id)</li>
</ul>
<strong>Formats: </strong>xml, json
</p>

<h3>Delete</h3>
<p>Removes a direct message from the authenticating user's inbox and returns removed message's details in the desired format or an error message in case of an error.</p>
<p>
<strong>URL: </strong><pre>{$base_href}api/direct_messages/delete.[format].[api_key]</pre><br />
<strong>Method: </strong><pre>POST</pre><br />
<strong>Required parameters: </strong><br />
<ul>
<li><pre>username</pre> (authenticating user's username)</li>
<li><pre>password</pre> (authenticating user's password)</li>
<li><pre>message_id</pre> (ID of the message to be deleted)</li>
</ul>
<strong>Formats: </strong>xml, json
</p>

<h2>User methods</h2>
<h3>Friends</h3>
<p>Returns specified user's friends in the desired format.</p>
<ul>
<li><strong>URL: </strong><pre>{$base_href}api/users/friends/[user].[format].[api_key]</pre></li>
<li><strong>Formats:</strong> xml, json</li>
<li><strong>Usage example:<pre> {$base_href}api/users/friends/johnny.xml</pre> ou <pre>{$base_href}api/users/friends/12345.json</pre></strong></li>
</ul>

<h3>Followers</h3>
<p>Returns specified user's followers in the desired format.</p>
<p>
<strong>URL: </strong><pre>{$base_href}api/users/followers/[user].[format].[api_key]</pre><br />
<strong>Formats:</strong> xml, json<br />
<strong>Usage example: </strong><pre>{$base_href}api/users/followers/diana.json</pre> ou <pre>{$base_href}api/users/followers/584.xml</pre>
</p>

<h3>User details</h3>
<p>Returns user's details in the desired format.</p>
<p>
<strong>URL: </strong><pre>{$base_href}api/users/details/[user].[format].[api_key]</pre><br />
<strong>Formats:</strong> xml, json<br />
<strong>Usage example:  </strong><pre>{$base_href}api/users/details/diana.json.f9cv8d97c90d8c6f2cab37bb6d1f1335</pre> ou <pre>{$base_href}api/users/details/584.xml.f9f16d97c90d8c6f23sb37bb6d1f1992</pre><br />
</p>

<h2>Friendship methods</h2>
<h3>Create</h3>
<p>Adds a specified user to the authenticating user's friends. Returns the befriended user's details in the desired format or an error message in case of an error.</p>
<p>
<strong>Method: </strong><pre>POST</pre><br />
<strong>Required parameters: </strong><br />
<ul>
<li><pre>username</pre> (authenticating user's username)</li>
<li><pre>password</pre> (authenticating user's password)</li>
<li><pre>friend</pre> (befriended user's username or ID)</li>
</ul>
<strong>URL: </strong><pre>{$base_href}api/friendships/create.[format].[api_key]</pre><br />
<strong>Formats:</strong> xml, json
</p>

<h3>Destroy</h3>
<p>Removes a specified (leaves) user from the authenticating user's friend list. Returns the removed user's details in the desired format ro an error message in case of an error. </p>
<p>
<strong>Method: </strong><pre>POST</pre><br />
<strong>Required parameters: </strong><br />
<ul>
<li><pre>username</pre> (authenticating user's username)</li>
<li><pre>password</pre> (authenticating user's password)</li>
<li><pre>friend</pre> (left user's username or ID)</li>
</ul>
<strong>URL: </strong><pre>{$base_href}api/friendships/destroy.[format].[api_key]</pre><br />
<strong>Formats:</strong> xml, json
</p>