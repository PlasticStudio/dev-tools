<ul $AttributesHTML>
	<% loop $Options %>
		<li class="option">
			<input id="$ID" class="radio" name="$Name" type="radio" value="$Value"<% if $isChecked %> checked<% end_if %> />
			<label for="$ID">
                <img src="$Value" />
            </label>
		</li>
	<% end_loop %>
</ul>
