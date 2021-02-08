context('Base test', () => {
    beforeEach(() => {
        cy.visit('http://localhost:8101/production.php')
    })

    it('is translating from files', () => {
        cy.xpath("//*[contains(text(), 'Hello world!')]").should("be.visible");
    })
});