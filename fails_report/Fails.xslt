<?xml version="1.0" encoding="utf-8"?>
<!-- Created with Liquid XML Studio - FREE Community Edition 7.0.5.906 (http://www.liquid-technologies.com) -->
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
	<xsl:output method="html" encoding="utf-8" media-type="text/html" doctype-public="-//W3C//DTD HTML 4.01//EN" doctype-system="http://www.w3.org/TR/html4/strict.dtd" />
	<xsl:template match="/">
		<html>
			<head>
				<title>Fails</title>
                                <script type="text/javascript" src="../js/jquery-1.3.2.min.js"></script>
				<script type="text/javascript" src="main.js"></script>
				<style type="text/css">
					body {
						background: black;
					}
					.date {
						background: #DDEEFF;
						font: 18pt serif;
						text-align: center;
						cursor: pointer;
					}
					.platform_and_build {
						background: #CCDDEE;
						font: 16pt serif;
						text-align: center;
					}
					.testrun {
						background: white;
						font: 11pt serif;
						text-align: left;
					}
					.testrun_name {
						background: #BBCCDD;
						font: bold 13pt serif;
						text-align: center;
					}
					.even {
						background: #DDDDDD;
					}
					.table_header {
						text-align: center;
						font: bold 12pt serif;
					}
                </style>
			</head>
			<body>
				<xsl:for-each select="/Root/Date">
					<div class="date">
						<xsl:variable name="id0" select="concat('date_', @Value)" />
						<xsl:attribute name="id"><xsl:value-of select="$id0" /></xsl:attribute>
						<xsl:value-of select="@Value" />
					</div>
					<div class="need_to_toggle">
						<xsl:for-each select="Fails">
							<div class="platform_and_build">
								<!--<xsl:variable name="id1" select="concat('toggable_', $id0, '_', position())" />
								<xsl:attribute name="id"><xsl:value-of select="$id1" /></xsl:attribute>-->
								<xsl:value-of select="@Build" /> on <xsl:value-of select="@Platform" />
								<xsl:for-each select="TestRun">
									<div class="testrun">
										<div class="testrun_name"><xsl:value-of select="@Name" /></div>
										<table width="100%" border="1" cellpadding="1" cellspacing="1" bordercolor="black">
											<tr class="table_header">
												<td>Test Name</td>
												<td>Log from SilkRoad DB</td>
												<td>Description</td>
                                            </tr>
											<xsl:for-each select="Fail">
												<tr>
													<xsl:if test="position() mod 2 = 0"><xsl:attribute name="class">even</xsl:attribute></xsl:if>
													<td width="30%" class="test_name_cell"><xsl:value-of select="TestName" /></td>
													<td width="20%"><xsl:value-of select="DBLog" /></td>
													<td width="50%"><xsl:value-of select="Description" /></td>
												</tr>
											</xsl:for-each>
										</table>
									</div>
								</xsl:for-each>
							</div>
						</xsl:for-each>						
                    </div>
				</xsl:for-each>
			</body>
		</html>
	</xsl:template>
</xsl:stylesheet>
