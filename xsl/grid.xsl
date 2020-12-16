<?xml version="1.0" encoding="ISO-8859-1"?>
<xsl:stylesheet version="1.0"
xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    <xsl:template match="/">
        <h3>Telephone Telepathy Experiment: Results Detail</h3>
            <xsl:call-template name="menu"/>
                <form id="grid_form_id">
                    <table class="list">
                        <tr>
                        	<th class="th1">Experiment</th>
                            <th class="th1">Group Name</th>
                            <th class="th1">Trial</th>
                            <th class="th1">Start Date</th>
                            <th class="th1">Start Time</th>
                            <th class="th1">SMS Date</th>
                            <th class="th1">SMS Time</th>
                            <th class="th1">Call Date</th>
                            <th class="th1">Call Time</th>
                            <th class="th1">Guess Date</th>
                            <th class="th1">Guess Time</th>
                            <th class="th1">End Date</th>
                            <th class="th1">End Time</th>
                            <th class="th1">Participant 1</th>
                            <th class="th1">Participant 2</th>
                            <th class="th1">Participant 3</th>
                            <th class="th1">Caller Guess</th>
                            <th class="th1">Caller Actual</th>
                            <th class="th1">Hit</th>
                            <th class="th1">Status</th>
                        </tr>
                        <xsl:for-each select="data/grid/row">
                            <xsl:element name="tr">
                                <xsl:attribute name="experiment_id">
                                    <xsl:value-of select="experiment_id" />
                                </xsl:attribute>
                                <td><xsl:value-of select="group_name" /></td>
                                <td><xsl:value-of select="trial_num" /></td>
                                <td><xsl:value-of select="start_date" /></td>
                                <td><xsl:value-of select="start_time" /></td>
                                <td><xsl:value-of select="sms_date" /></td>
                                <td><xsl:value-of select="sms_time" /></td>
                                <td><xsl:value-of select="call_date" /></td>
                                <td><xsl:value-of select="call_time" /></td>
                                <td><xsl:value-of select="guess_date" /></td>
                                <td><xsl:value-of select="guess_time" /></td>
                                <td><xsl:value-of select="participant_1" /></td>
                                <td><xsl:value-of select="participant_2" /></td>
                                <td><xsl:value-of select="participant_3" /></td>
                                <td><xsl:value-of select="caller_guess" /></td>
                                <td><xsl:value-of select="caller_actual" /></td>
                                <td><xsl:value-of select="caller_phone" /></td>
                                <td><xsl:value-of select="extension" /></td>
                                <td><xsl:value-of select="hit" /></td>
                                <td><xsl:value-of select="status" /></td>
                            </xsl:element>
                        </xsl:for-each>
                    </table>
                </form>
        <xsl:call-template name="menu" />
    </xsl:template>
    <xsl:template name="menu">
        <xsl:for-each select="data/params">
       <!--     <table>
                <tr>
                    <td class="left">
                        <xsl:value-of select="num_records" /> Trials
                    </td>
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
                        of <xsl:value-of select="" />
                    </td>
                </tr>
            </table>   -->
        </xsl:for-each>
    </xsl:template>
</xsl:stylesheet>