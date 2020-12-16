<?xml version="1.0" encoding="ISO-8859-1"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    <xsl:template match="/">
        <h3>Results Detail</h3>
        <xsl:call-template name="menu" />
        <xsl:call-template name="select" />
        <form id="results_grid">
            <table class="detail">
                <tr>
                    <th class="th">Experimenter</th>
                    <th class="th">Exp</th>
                    <th class="th">Trial</th>
                    <th class="th">Start Date</th>
                    <th class="th">Start Time</th>
                    <th class="th">SMS Date</th>
                    <th class="th">SMS Time</th>
                    <th class="th">Call Date</th>
                    <th class="th">Call Time</th>
                    <th class="th">Guess Date</th>
                    <th class="th">Guess Time</th>
                    <th class="th">End Date</th>
                    <th class="th">End Time</th>
                    <th class="th">Friend 1</th>
                    <th class="th">Friend 2</th>
                    <th class="th">Guess</th>
                    <th class="th">Actual</th>
                    <th class="th">Hit</th>
                    <th class="th">Status</th>
                </tr>
                <xsl:for-each select="data/grid/row">
                    <xsl:element name="tr">
                        <td class="friend"><xsl:value-of select="experimenter" /></td>
                        <td class="data"><xsl:value-of select="experiment_id" /></td>
                        <td class="data"><xsl:value-of select="trial_num" /></td>
                        <td class="datetime"><xsl:value-of select="start_date" /></td>
                        <td class="datetime"><xsl:value-of select="start_time" /></td>
                        <td class="datetime"><xsl:value-of select="sms_date" /></td>
                        <td class="datetime"><xsl:value-of select="sms_time" /></td>
                        <td class="datetime"><xsl:value-of select="call_date" /></td>
                        <td class="datetime"><xsl:value-of select="call_time" /></td>
                        <td class="datetime"><xsl:value-of select="guess_date" /></td>
                        <td class="datetime"><xsl:value-of select="guess_time" /></td>
                        <td class="datetime"><xsl:value-of select="end_date" /></td>
                        <td class="datetime"><xsl:value-of select="end_time" /></td>
                        <td class="friend"><xsl:value-of select="participant_1" /></td>
                        <td class="friend"><xsl:value-of select="participant_2" /></td>
                        <td class="friend"><xsl:value-of select="caller_guess" /></td>
                        <td class="friend"><xsl:value-of select="caller_actual" /></td>
                        <td class="data"><xsl:value-of select="hit" /></td>
                        <td class="data"><xsl:value-of select="status" /></td>
                    </xsl:element>
                </xsl:for-each>
            </table>
        </form>
    </xsl:template>
    <xsl:template name="menu">
        <xsl:for-each select="data/params">
            <table>
                <tr>
                    <td class="right">
                        <xsl:choose>
                        <xsl:when test="prev_page>0">
                        <xsl:element name="a" >
                        <xsl:attribute name="href" >#</xsl:attribute>
                        <xsl:attribute name="onclick">
                            loadGridPage(<xsl:value-of select="prev_page"/>)
                        </xsl:attribute>
                            Previous page
                        </xsl:element>
                        </xsl:when>
                        </xsl:choose>
                    </td>
                    <td class="left">
                        <xsl:choose>
                        <xsl:when test="next_page>0">
                        <xsl:element name="a">
                        <xsl:attribute name = "href" >#</xsl:attribute>
                        <xsl:attribute name = "onclick">
                            loadGridPage(<xsl:value-of select="next_page"/>)
                        </xsl:attribute>
                            Next page
                        </xsl:element>
                        </xsl:when>
                        </xsl:choose>
                    </td>
                    <td class="right">
                        page <xsl:value-of select="return_page" />
                        of <xsl:value-of select="total_pages" />
                    </td>
                </tr>
            </table>
        </xsl:for-each>
    </xsl:template>
    <xsl:template name="select">
    </xsl:template>
</xsl:stylesheet>