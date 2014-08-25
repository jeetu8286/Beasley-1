<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0" xmlns:str="http://exslt.org/strings" xmlns:set="http://exslt.org/sets">
	<xsl:variable name="apos">'</xsl:variable>
	<xsl:variable name="quot">"</xsl:variable>
	<xsl:variable name="quot-entity"><![CDATA[&quot;]]></xsl:variable>

	<!-- Convert a boolean string like "True" to a JSON bool value -->
	<xsl:template name="boolstr-to-bool">
		<xsl:param name="boolstr" />
		<xsl:choose>
			<xsl:when test="'True' = $boolstr">true</xsl:when>
			<xsl:otherwise>false</xsl:otherwise>
		</xsl:choose>
	</xsl:template>

	<!-- Convert a boolean numeric string like "1" to a JSON bool value -->
	<xsl:template name="boolnumstr-to-bool">
		<xsl:param name="boolnumstr" />
		<xsl:choose>
			<xsl:when test="'1' = $boolnumstr">true</xsl:when>
			<xsl:otherwise>false</xsl:otherwise>
		</xsl:choose>
	</xsl:template>

	<xsl:template match="/">
		{
		"settings": {
		"importFormat": "gigya-raas-import",
		"apiKey": "%api_key%",
		"profilePhotoDomains": "",
		"finalizeRegistration": true,
		"skipVerification": true,
		"totalRecords": <xsl:value-of select="count(Members/Member)"/>
		},
		"accounts": [
			<xsl:for-each select="Members/Member">
				<xsl:if test="position()!=1">,</xsl:if> {
				"UID": "<xsl:value-of select="@MemberGUID"/>",
				"hashedPassword": "%password%",
				"pwHashAlgorithm": "md5",
				"email": "<xsl:value-of select="@EmailAddress"/>",
				"username": "<xsl:value-of select="@ScreenName"/>",
				"profile": {
					"firstName": "<xsl:value-of select="@FirstName"/>",
					"lastName": "<xsl:value-of select="@LastName"/>",
					"nickname": "<xsl:value-of select="@ScreenName"/>",
					"email": "<xsl:value-of select="@EmailAddress"/>",
					<xsl:if test="str:split(@Birthday, '/')[2]">"birthDay": <xsl:value-of select="str:split(@Birthday, '/')[2]"/>,</xsl:if>
					<xsl:if test="str:split(@Birthday, '/')[1]">"birthMonth": <xsl:value-of select="str:split(@Birthday, '/')[1]"/>,</xsl:if>
					<xsl:if test="str:split(@Birthday, '/')[3]">"birthYear": <xsl:value-of select="str:split(@Birthday, '/')[3]"/>,</xsl:if>
					"city": "<xsl:value-of select="@City"/>",
					"state": "<xsl:value-of select="@State"/>",
					"zip": "<xsl:value-of select="@ZipCode"/>",
					"country": "<xsl:value-of select="@Country"/>"
				},
				"data": {
					"status": "<xsl:value-of select="@Status"/>",
					"source": "<xsl:value-of select="@Source"/>",
					"sourceDetail": "<xsl:value-of select="@SourceDetail"/>",
					"UTCJoinDate": "<xsl:value-of select="@UTCJoinDate"/>",
					"gender": "<xsl:value-of select="@Gender"/>",
					"address": "<xsl:value-of select="@Address1"/>",
					"longitude": "<xsl:value-of select="@Longitude"/>",
					"latitude": "<xsl:value-of select="@Latitude"/>",
					"bounceBackCount": "<xsl:value-of select="@BounceBackCount"/>",
					"isEmailUsable": <xsl:call-template name="boolnumstr-to-bool"><xsl:with-param name="boolnumstr" select="@IsEmailUsable"></xsl:with-param></xsl:call-template>,
					"isCommentingSiteAuthor": <xsl:call-template name="boolstr-to-bool"><xsl:with-param name="boolstr" select="@IsCommentingSiteAuthor"></xsl:with-param></xsl:call-template>,
					"isSubscribedToCommentReplies": <xsl:call-template name="boolstr-to-bool"><xsl:with-param name="boolstr" select="@IsSubscribedToCommentReplies"></xsl:with-param></xsl:call-template>,
					"isBannedFromCommenting": <xsl:call-template name="boolstr-to-bool"><xsl:with-param name="boolstr" select="@IsBannedFromCommenting"></xsl:with-param></xsl:call-template>,
					"groups": [ <xsl:for-each select="MemberGroups/MemberGroup">
						<xsl:if test="position()!=1">,</xsl:if> {"name": "<xsl:value-of select="@Name"/>", "subscribed": "<xsl:value-of select="@UTCDateSubscribed"/>"}
					</xsl:for-each> ],
					"contests": [ <xsl:for-each select="MemberSiteInteraction/Contests/Contest">
						<xsl:if test="position()!=1">,</xsl:if>{
							"id": "<xsl:value-of select="@ContestID"/>",
							"name": "<xsl:value-of select="@ContestName"/>",
							"category": "<xsl:value-of select="@ContestCategoryDescription"/>",
							"giveawayType": "<xsl:value-of select="@ContestGiveawayTypeDescription"/>",
							"startDate": "<xsl:value-of select="@StartDate"/>",
							"endDate": "<xsl:value-of select="@EndDate"/>",
							"isNonClubContest": <xsl:call-template name="boolstr-to-bool"><xsl:with-param name="boolstr" select="@IsNonClubContest"></xsl:with-param></xsl:call-template>,
							"isFullMembershipRequired": <xsl:call-template name="boolstr-to-bool"><xsl:with-param name="boolstr" select="@IsFullMembershipRequired"></xsl:with-param></xsl:call-template>,
							"entries": [ <xsl:for-each select="set:distinct(ContestEntries/ContestEntry/@EntryID)">
								<xsl:variable name="entryID" select="."/>
								<xsl:if test="position()!=1">,</xsl:if> {
								"date": "<xsl:value-of select="../../ContestEntry[@EntryID = $entryID]/@UTCEntryDate"/>",
								"fields": [ <xsl:for-each select="../../ContestEntry[@EntryID = $entryID]">
									<xsl:if test="position()!=1">,</xsl:if> {
										"field": "<xsl:value-of select="@FieldHeadline"/>",
										"value": "<xsl:value-of select="str:replace(@Response, $quot, $quot-entity)"/>"
									}
								</xsl:for-each> ]
							}
							</xsl:for-each> ]
						}
					</xsl:for-each> ],
					"surveys": [ <xsl:for-each select="MemberSiteInteraction/Surveys/Survey">
						<xsl:if test="position()!=1">,</xsl:if> {
							"id": "<xsl:value-of select="@SurveyID"/>",
							"name": "<xsl:value-of select="@SurveyName"/>",
							"startDate": "<xsl:value-of select="@UTCStartDateTime"/>",
							"endDate": "<xsl:value-of select="@UTCEndDateTime"/>",
							"isAssociatedToContest": "<xsl:value-of select="@IsAssociatedToContest"/>",
							"entries": [ <xsl:for-each select="SurveyEntries/SurveyEntry">
								<xsl:if test="position()!=1">,</xsl:if> {
									"id": "<xsl:value-of select="@UserSurveyID"/>",
									"date": "<xsl:value-of select="@UTCCompletionDate"/>",
									"questions": [ <xsl:for-each select="Questions/Question">
										<xsl:if test="position()!=1">,</xsl:if> {
											"question": "<xsl:value-of select="str:replace(@Question, $quot, $quot-entity)"/>",
											"answers": [ <xsl:for-each select="Responses/Response">
												<xsl:if test="position()!=1">,</xsl:if>"<xsl:value-of select="str:replace(@Value, $quot, $quot-entity)"/>"
											</xsl:for-each> ]
										}
									</xsl:for-each> ]
								}
							</xsl:for-each> ]
						}
					</xsl:for-each> ]
				}
			}
			</xsl:for-each>
		]
		}
	</xsl:template>
</xsl:stylesheet>