<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0" xmlns:str="http://exslt.org/strings" xmlns:set="http://exslt.org/sets">
	<xsl:template match="/">
		{
		"settings": {
		"importFormat": "gigya-raas-import",
		"apiKey": "",
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
				"email": "john.smith@acme.com",
				"username": "<xsl:value-of select="@ScreenName"/>",
				"profile": {
					"firstName": "<xsl:value-of select="@FirstName"/>",
					"lastName": "<xsl:value-of select="@LastName"/>",
					"nickname": "<xsl:value-of select="@ScreenName"/>",
					"email": "<xsl:value-of select="@EmailAddress"/>",
					"photoURL": "",
					"profileURL": "",
					"birthDay": <xsl:value-of select="str:split(@Birthday, '/')[2]"/>,
					"birthMonth": <xsl:value-of select="str:split(@Birthday, '/')[1]"/>,
					"birthYear": <xsl:value-of select="str:split(@Birthday, '/')[3]"/>,
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
					"bounchBackCount": "<xsl:value-of select="@BounceBackCount"/>",
					"isEmailUsable": "<xsl:value-of select="@IsEmailUsable"/>",
					"isCommentingSiteAuthor": "<xsl:value-of select="@IsCommentingSiteAuthor"/>",
					"isSubscribedToCommentReplies": "<xsl:value-of select="@IsSubscribedToCommentReplies"/>",
					"isBannedFromCommenting": "<xsl:value-of select="@IsBannedFromCommenting"/>",
					"groups": [ <xsl:for-each select="MemberGroups/MemberGroup">
						<xsl:if test="position()!=1">,</xsl:if> {"name": "<xsl:value-of select="@Name"/>", "subscribed": "<xsl:value-of select="@UTCDateSubscribed"/>"}
					</xsl:for-each> ],
					"contests": [ <xsl:for-each select="MemberSiteInteraction/Contests/Contest">
						{
							"id": "<xsl:value-of select="@ContestID"/>",
							"name": "<xsl:value-of select="@ContestName"/>",
							"category": "<xsl:value-of select="@ContestCategoryDescription"/>",
							"giveawayType": "<xsl:value-of select="@ContestGiveawayTypeDescription"/>",
							"startDate": "<xsl:value-of select="@StartDate"/>",
							"endDate": "<xsl:value-of select="@EndDate"/>",
							"isNonClubContest": "<xsl:value-of select="@IsNonClubContest"/>",
							"isFullMembershipRequired": "<xsl:value-of select="@IsFullMembershipRequired"/>",

							"entries": [ <xsl:for-each select="set:distinct(ContestEntries/ContestEntry/@EntryID)">
								<xsl:variable name="entryID" select="."/>
								"date": "<xsl:value-of select="../../ContestEntry[@EntryID = $entryID]/@UTCEntryDate"/>",
								"fields": [ <xsl:for-each select="../../ContestEntry[@EntryID = $entryID]">
									<xsl:if test="position()!=1">,</xsl:if> {
										"field": "<xsl:value-of select="@FieldHeadline"/>",
										"value": "<xsl:value-of select="@Response"/>"
									}
								</xsl:for-each> ],
							</xsl:for-each> ]
						},
					</xsl:for-each> ],
					"surveys": [ <xsl:for-each select="MemberSiteInteraction/Surveys/Survey">
						<xsl:if test="position()!=1">,</xsl:if> {
							"id": "<xsl:value-of select="@SurveyID"/>",
							"name": "<xsl:value-of select="@SurveyName"/>",
							"startDate": "<xsl:value-of select="@UTCStartDateTime"/>",
							"endDate": "<xsl:value-of select="@UTCEndDateTime"/>",
							"isAssociatedToContest": "<xsl:value-of select="@IsAssociatedToContest"/>",
							"entries": [ <xsl:for-each select="SurveyEntries/SurveyEntry">
								"id": "<xsl:value-of select="@UserSurveyID"/>",
								"date": "<xsl:value-of select="@UTCCompletionDate"/>",
								"questions": [ <xsl:for-each select="Questions/Question">
									<xsl:if test="position()!=1">,</xsl:if> {
										"question": "<xsl:value-of select="@Question"/>",
										"answers": [ <xsl:for-each select="Responses/Response">
											<xsl:if test="position()!=1">,</xsl:if>"<xsl:value-of select="@Value"/>"
										</xsl:for-each> ]
									}
								</xsl:for-each> ]
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