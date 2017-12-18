<html>
<body style="background: #EEEEEE;">
	<div style="font-size: 13px; font-family: Arial, Helvetica, sans-serif;">
		<div style="max-width: 550px; margin: 0 auto; background: #FFFFFF; border-top: 20px solid #EEEEEE; border-bottom: 20px solid #EEEEEE;">
		
			<div style="border-top: 30px solid #FFFFFF; text-align: center;">
				<a href="{$AbsoluteDomain}" style="border: 0; text-decoration: none;">
					<img src="{$AbsoluteDomain}dev-tools/images/plasticstudio-logo.jpg" title="PlasticStudio logo" />
				</a>
			</div>
			
			<div style="border: 30px solid #FFFFFF;">

				<div style="font-size: 18px; font-weight: bold; border-bottom: 20px solid #FFFFFF; color: #555555;">
					$Title
				</div>
				
				<div style="border-bottom: 20px solid #FFFFFF;">
					$Intro
				</div>

				<table cellpadding="5" cellspacing="0" border="0" bgcolor="#ffffff" style="font-size: 13px; font-family: Arial, Helvetica, sans-serif;">
					<% loop Data %>
						<% if IsHeading %>
							<tr>
								<td colspan="2" style="vertical-align: top;">&nbsp;</td>
							</tr>
							<tr>
								<td colspan="2" style="vertical-align: top; font-size: 16px; color: #84dc00;">
									<strong>{$Value}</strong>
								</td>
							</tr>
						<% else %>
							<tr>
								<td style="vertical-align: top;" width="30%">
									<strong>{$Title}:</strong>
								</td>
								<td>
									<% if Link %>
										<a href="{$Link}" target="_blank">$Value</a>
									<% else %>
										<span>$Value</span>
									<% end_if %>
								</td>
							</tr>
						<% end_if %>
					<% end_loop %>
				</table>

			</div>
			
		</div>

		<div style="width: 550px; margin: 0 auto; color: #BBBBBB; border-bottom: 20px solid #EEEEEE; text-align: center; font-size: 11px;">
			Sent at $TimeSent from $URL
		</div>
		
	</div>
</body>
</html>