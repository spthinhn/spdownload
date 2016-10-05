<?php

OW::getRouter()->addRoute(new OW_Route('spdownload.category_index', 'downloads/category', "SPDOWNLOAD_CTRL_Category", 'index'));
OW::getRouter()->addRoute(new OW_Route('spdownload.category_update', 'downloads/category/:id/:name', "SPDOWNLOAD_CTRL_Category", 'index'));


OW::getRouter()->addRoute(new OW_Route('spdownload.index', 'downloads', "SPDOWNLOAD_CTRL_Download", 'index'));

OW::getRouter()->addRoute(new OW_Route('spdownload.upload_index', 'downloads/new', "SPDOWNLOAD_CTRL_Upload", 'index'));
OW::getRouter()->addRoute(new OW_Route('spdownload.upload_file', 'downloads/uploadFile', "SPDOWNLOAD_CTRL_Upload", 'uploadFile'));