<?php /* #?ini charset="utf-8"?

# Array with eztube cache items to extend ezcache.
# By default, standard lists, playlists, etc. are cached grouped as 'other'.
[Cache]
CacheItems[]=eztube_images
CacheItems[]=eztube_comments
CacheItems[]=eztube_videos
CacheItems[]=eztube_users
# If you want to enable seperate caching of playlists and standard feeds,
# uncomment settings below and under the CacheSettings group in eztube.ini.
#CacheItems[]=eztube_playlists
#CacheItems[]=eztube_standardfeeds
CacheItems[]=eztube_other

[Cache_eztube_images]
name=eZ Tube images
id=eztube-images
isClustered=true
expiryKey=eztube-images
enabled=true
class=EZTubeImageCache
purgeClass=EZTubeImageCache

[Cache_eztube_comments]
name=eZ Tube XML cache - comments
id=eztube-xml-comments
isClustered=true
expiryKey=eztube-xml-comments
enabled=true
class=EZTubeXMLCache
purgeClass=EZTubeXMLCache

[Cache_eztube_videos]
name=eZ Tube XML cache - videos
id=eztube-xml-videos
isClustered=true
expiryKey=eztube-xml-videos
enabled=true
class=EZTubeXMLVideoCache
purgeClass=EZTubeXMLVideoCache

[Cache_eztube_users]
name=eZ Tube XML cache - users
id=eztube-xml-users
isClustered=true
expiryKey=eztube-xml-users
enabled=true
class=EZTubeXMLUserCache
purgeClass=EZTubeXMLUserCache

#[Cache_eztube_playlists]
#name=eZ Tube XML cache - playlists
#id=eztube-xml-playlists
#isClustered=true
#expiryKey=eztube-xml-playlists
#enabled=true
#class=EZTubeXMLPlaylistCache
#purgeClass=EZTubeXMLPlaylistCache

#[Cache_eztube_standardfeeds]
#name=eZ Tube XML cache - standard feeds
#id=eztube-xml-standardfeeds
#isClustered=true
#expiryKey=eztube-xml-standardfeeds
#enabled=true
#class=EZTubeXMLPlaylistCache
#purgeClass=EZTubeXMLPlaylistCache

[Cache_eztube_other]
name=eZ Tube XML cache - other.
id=eztube-xml-other
isClustered=true
expiryKey=eztube-xml-other
enabled=true
class=EZTubeXMLCache
purgeClass=EZTubeXMLOtherCache

[RegionalSettings]
TranslationExtensions[]=eztube


*/ ?>
