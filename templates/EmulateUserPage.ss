<!DOCTYPE HTML>
<head>

	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scaleable=no" name="viewport">
	
	<title>Emulate User</title>
	
	<% base_tag %>

</head>

<body id="dev-tools" class="emulate-user">

	<section class="emulate-user">
		
		<h1 class="title">Emulate user</h2>
		
		<div class="intro">Please select the user that you wish to emulate. Once finished emulating a user, you need to log out, and then log back in as an Administrator.</div>
		
		<div class="table">
			
			<div class="headings">
				<div class="id">ID</div>
				<div class="name">Name</div>
				<div class="email">Email</div>
				<br style="clear: both;" />
			</div>
			
			<div class="users">
                <% if Users %>
                    <% loop Users %>
						<% if ID == CurrentMember.ID %>
							<span class="user current">
								<div class="id">$ID</div>
								<div class="name">$Name (You)</div>
								<div class="email">$Email</div>
								<br style="clear: both;" />
							</span>
						<% else %>
							<a href="{$Top.Link}emulateuser/{$ID}" class="user">
								<div class="id">$ID</div>
								<div class="name">$Name</div>
								<div class="email">$Email</div>
								<br style="clear: both;" />
							</a>
						<% end_if %>
                    <% end_loop %>
                <% else %>
                    <p class="no-results">No users to emulate</p>
                <% end_if %>
			</div>
		</div>
		
		<% with Users %>
			<% if MoreThanOnePage %>    

				<div class="pagination center">
					<div class="liner">
					
						<% if NotFirstPage %>
							<a class="prev button blue readmore" href="$PrevLink" title="View the previous page">Prev</a>
						<% else %>	
							<span class="prev disabled button">Prev</span>
						<% end_if %>
						
						<span class="current">
							Page $CurrentPage of $TotalPages
						</span>
						
						<% if NotLastPage %>
							<a class="next button blue readmore" href="$NextLink" title="View the next page">Next</a>
						<% else %>	
							<span class="next disabled button">Next</span>
						<% end_if %>
						
					</div>
				</div>
				
			<% end_if %>
		<% end_with %>
		
	</section>

	<% include DevToolsFooter %>
	
</body>
</html>