<?php /* #?ini charset="utf-8"?

# Settings to control display on the video homepage (eztube/home).
# Separate search terms by a pipe |
# Separate search parameters by a colon ;
# A list of all available search parameters is available at: 
# http://bit.ly/cXO8K7
# When modifying search terms on this page, always ensure that the
# default search and filter settings in eztube.ini.append.php use matching
# terms and parameters.

[eztube_home_row_1]
# search for videos.
Module=eztube
FunctionName=search
#Constant[keywords]=ezpublish|ez publish -tutorial
Constant[keywords]=rihanna|Rihanna -Eminem -clinton -Drake -Justin
Constant[filters]=true
Constant[limit]=20
Constant[order]=viewCount
Title=Rihanna Music Videos

[eztube_home_row_2]
# search for videos.
Module=eztube
FunctionName=search
Constant[keywords]=
Constant[filters]=true
Constant[params]=author=RihannaVEVO;safeSearch=none
Constant[offset]=0
Constant[limit]=10
Constant[order]=published
Title=From Rihanna

[eztube_home_row_3]
# search for videos.
Module=eztube
FunctionName=search
Constant[keywords]=live concert -Eminem -clinton -Drake -Justin -Chris
Constant[params]=
Constant[offset]=0
Constant[limit]=10
Constant[order]=relevance
Constant[filters]=true
Title=Rihanna in Concert

[eztube_home_row_4]
# search for users
Module=eztube
FunctionName=search
Constant[keywords]=rihanna
Constant[searchtype]=users
Constant[params]=
Constant[offset]=0
Constant[limit]=20
Constant[order]=relevance
Constant[filters]=true
Title=Rihanna Fans
?>
