<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    <xsl:template match="/"> <!-- Από την ρίζα του XML -->
        <html>
            <head>
                <title>Περιβαλλοντικές Δράσεις</title>
                <style>
                    body {
                         font-family: Arial, sans-serif; 
                         margin: 20px;
                          }
                    .action-box {
                         border: 1px solid #ccc; 
                         padding: 10px; 
                         margin-bottom: 10px; 
                         border-radius: 5px;
                          }
                    img {
                         max-width: 200px;
                          display: block;
                           margin-top: 10px; 
                           }
                    .stats { 
                        background: #f4f4f4; 
                        padding: 10px; 
                        border-left: 5px solid #2ecc71;
                         margin-bottom: 20px;                         
                        }
                    div.action-box {
                         background: #b5e4b9;
                          }
                </style>
            </head>
            <body>
                <h1>Κατηγορία: <xsl:value-of select="actions/action[1]/category"/></h1>

                <div class="stats">
                    <strong>Συνολικό πλήθος δράσεων στην κατηγορία: </strong>
                    <xsl:value-of select="count(actions/action)"/>
                </div>

                <xsl:for-each select="actions/action">
                    <div class="action-box">
                        <h2><xsl:value-of select="title"/></h2>
                        <p><xsl:value-of select="subtitle"/></p>
                        <p><strong>Τοποθεσία:</strong> <xsl:value-of select="location/location_name"/>, <xsl:value-of select="location/municipality"/></p>
                        <p><strong>Διάρκεια:</strong> <xsl:value-of select="date/start_date"/> 
                            <xsl:if test="date/end_date"> έως <xsl:value-of select="date/end_date"/></xsl:if>
                        </p>
                        <xsl:if test="image != ''">
                            <img src="assets/img/actions/{image}" alt="{title}" />
                        </xsl:if>
                        <p><small>Καταχωρήθηκε: <xsl:value-of select="created_at"/></small></p>
                    </div>
                </xsl:for-each>
            </body>
        </html>
    </xsl:template>
</xsl:stylesheet>