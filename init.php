<?php

/* Begin Category */
OW::getRouter()->addRoute(new OW_Route('spdownload.category_index', 'downloads/category', "SPDOWNLOAD_CTRL_Category", 'index'));
OW::getRouter()->addRoute(new OW_Route('spdownload.category_update', 'downloads/category/:params', "SPDOWNLOAD_CTRL_Category", 'index'));
OW::getRouter()->addRoute(new OW_Route('spdownload.category_delete', 'downloads/category/delete/:params', "SPDOWNLOAD_CTRL_Category", 'deleteCategory'));
/* End Category */

/* Begin Download */
OW::getRouter()->addRoute(new OW_Route('spdownload.index', 'downloads', "SPDOWNLOAD_CTRL_Download", 'index'));
/* End Download */

/* Begin platform */
OW::getRouter()->addRoute(new OW_Route('spdownload.platform_index', 'downloads/platform', "SPDOWNLOAD_CTRL_Platform", 'index'));
OW::getRouter()->addRoute(new OW_Route('spdownload.platform_add', 'downloads/platform/add', "SPDOWNLOAD_CTRL_Platform", 'add'));
/* End platform */

/* Begin Upload */
OW::getRouter()->addRoute(new OW_Route('spdownload.upload_index', 'downloads/new', "SPDOWNLOAD_CTRL_Upload", 'index'));
OW::getRouter()->addRoute(new OW_Route('spdownload.upload_file', 'downloads/uploadFile', "SPDOWNLOAD_CTRL_Upload", 'uploadFile'));
OW::getRouter()->addRoute(new OW_Route('spdownload.resumable', 'downloads/resumable', "SPDOWNLOAD_CTRL_Upload", 'resumable'));
/* End Upload */