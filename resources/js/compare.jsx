import React from 'react';
import { createRoot } from 'react-dom/client';

import CompareButton from './react/components/compare/CompareButton';
import ComparePage from './react/pages/ComparePage';


document
    .querySelectorAll('[data-compare-root]')
    .forEach(function (element) {

        const bikeId = Number(
            element.dataset.bikeId
        );

        const initiallyCompared =
            element.dataset.compared === 'true';

        createRoot(element).render(
            <CompareButton
                bikeId={bikeId}
                initiallyCompared={initiallyCompared}
            />
        );
    });

const comparePageRoot = document.getElementById(
    'compare-page-root'
);

if (comparePageRoot) {
    const itemsUrl =
        comparePageRoot.dataset.itemsUrl;

    createRoot(comparePageRoot).render(
        <ComparePage
            itemsUrl={itemsUrl}
        />
    );
}
